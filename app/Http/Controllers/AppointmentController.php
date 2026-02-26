<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentCanceled;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentReminder;
use App\Models\Cita;
use App\Models\CitaStatusHistory;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;
        
        // Cancelar automáticamente las citas que pasaron sin confirmarse
        $this->cancelExpiredUnconfirmedAppointments($veterinariaId);
        
        $search = $request->input('search');
        $estado = $request->input('estado');
        $fecha = $request->input('fecha');
        
        $baseQuery = Cita::where('veterinaria_id', $veterinariaId)
            ->with(['cliente', 'mascota', 'mascotas', 'veterinario', 'agendadoPor'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('motivo', 'like', "%$search%")
                      ->orWhereHas('cliente', function ($clienteQuery) use ($search) {
                          $clienteQuery->where('nombre', 'like', "%$search%")
                                      ->orWhere('apellido', 'like', "%$search%");
                      })
                      ->orWhereHas('mascota', function ($mascotaQuery) use ($search) {
                          $mascotaQuery->where('nombre', 'like', "%$search%");
                      })
                      ->orWhereHas('mascotas', function ($mascotaQuery) use ($search) {
                          $mascotaQuery->where('nombre', 'like', "%$search%");
                      })
                      ->orWhereHas('veterinario', function ($vetQuery) use ($search) {
                          $vetQuery->where('nombre', 'like', "%$search%");
                      });
                });
            })
            ->when($estado, function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->when($fecha, function ($query, $fecha) {
                $query->whereDate('fecha', $fecha);
            });

        $appointments = (clone $baseQuery)
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        $today = today();
        $nowTime = now()->format('H:i:s');

        $todayAppointments = (clone $baseQuery)
            ->whereDate('fecha', $today)
            ->orderBy('hora')
            ->get();

        $tomorrowAppointments = (clone $baseQuery)
            ->whereDate('fecha', $today->copy()->addDay())
            ->orderBy('hora')
            ->get();

        $dayAfterAppointments = (clone $baseQuery)
            ->whereDate('fecha', $today->copy()->addDays(2))
            ->orderBy('hora')
            ->get();

        $upcomingToday = (clone $baseQuery)
            ->whereDate('fecha', $today)
            ->whereTime('hora', '>', $nowTime)
            ->orderBy('hora')
            ->get();

        $pastToday = (clone $baseQuery)
            ->whereDate('fecha', $today)
            ->whereTime('hora', '<=', $nowTime)
            ->orderBy('hora', 'desc')
            ->get();

        return view('appointments.index', compact(
            'appointments',
            'search',
            'todayAppointments',
            'tomorrowAppointments',
            'dayAfterAppointments',
            'upcomingToday',
            'pastToday'
        ));
    }

    public function create()
    {
        $veterinariaId = Auth::user()->tenant_id;
        $clients = Cliente::where('veterinaria_id', $veterinariaId)->get();
        $veterinarians = Veterinario::where('veterinaria_id', $veterinariaId)->where('activo', true)->get();
        $services = Servicio::where('veterinaria_id', $veterinariaId)->where('activo', true)->orderBy('nombre')->get();
        $products = Producto::where('veterinaria_id', $veterinariaId)->orderBy('nombre')->get();
        
        return view('appointments.create', compact('clients', 'veterinarians', 'services', 'products'));
    }

    public function store(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mascota_id' => 'nullable|exists:mascotas,id',
            'mascotas' => 'array',
            'mascotas.*' => 'integer|exists:mascotas,id',
            'veterinario_id' => 'required|exists:veterinarios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,cancelada,finalizada',
            'servicios' => 'array',
            'servicios.*' => 'integer|exists:servicios,id',
            'servicios_cantidad' => 'array',
            'servicios_cantidad.*' => 'nullable|integer|min:1',
            'productos' => 'array',
            'productos.*' => 'integer|exists:productos,id',
            'productos_cantidad' => 'array',
            'productos_cantidad.*' => 'nullable|integer|min:1',
        ]);

        $mascotaIds = $this->resolveMascotas($request, $validated['cliente_id'], $veterinariaId);
        $validated['mascota_id'] = $mascotaIds[0] ?? null;

        $validated['veterinaria_id'] = $veterinariaId;
        $validated['user_id'] = Auth::id();
        $validated = array_merge($validated, $this->statusTimestamps($validated['estado']));

        $serviceIds = array_map('intval', $request->input('servicios', []));
        $productIds = array_map('intval', $request->input('productos', []));

        $services = Servicio::whereIn('id', $serviceIds)
            ->where('veterinaria_id', $veterinariaId)
            ->get()
            ->keyBy('id');

        if (count($serviceIds) !== $services->count()) {
            throw ValidationException::withMessages([
                'servicios' => 'Uno o mas servicios no pertenecen a tu veterinaria.',
            ]);
        }

        $products = Producto::whereIn('id', $productIds)
            ->where('veterinaria_id', $veterinariaId)
            ->get()
            ->keyBy('id');

        if (count($productIds) !== $products->count()) {
            throw ValidationException::withMessages([
                'productos' => 'Uno o mas productos no pertenecen a tu veterinaria.',
            ]);
        }

        $serviciosSync = [];
        foreach ($serviceIds as $serviceId) {
            $cantidad = (int) ($request->input("servicios_cantidad.$serviceId") ?? 1);
            $serviciosSync[$serviceId] = ['cantidad' => max(1, $cantidad)];
        }

        $productosSync = [];
        foreach ($productIds as $productId) {
            $cantidad = (int) ($request->input("productos_cantidad.$productId") ?? 1);
            $productosSync[$productId] = ['cantidad' => max(1, $cantidad)];
        }

        DB::transaction(function () use ($validated, $serviciosSync, $productosSync, $products, $mascotaIds) {
            $appointment = Cita::create($validated);

            if (!empty($mascotaIds)) {
                $appointment->mascotas()->sync($mascotaIds);
            }

            if ($serviciosSync) {
                $appointment->servicios()->sync($serviciosSync);
            }

            if ($productosSync) {
                $appointment->productos()->sync($productosSync);
            }

            foreach ($productosSync as $productId => $pivot) {
                $cantidad = $pivot['cantidad'];
                $producto = $products->get($productId);

                if ($producto->stock_actual < $cantidad) {
                    throw ValidationException::withMessages([
                        'productos' => "Stock insuficiente para {$producto->nombre}.",
                    ]);
                }

                $producto->decrement('stock_actual', $cantidad);

                MovimientoStock::create([
                    'producto_id' => $productId,
                    'user_id' => Auth::id(),
                    'tipo' => 'salida',
                    'cantidad' => $cantidad,
                    'motivo' => 'Uso en cita',
                    'referencia' => "cita:{$appointment->id}",
                    'fecha' => now(),
                ]);
            }

            $this->recordStatusChange($appointment, null, $appointment->estado, 'Creacion de cita');
        });

        return redirect()->route('appointments.index')->with('success', 'Cita registrada exitosamente.');
    }

    public function edit(Cita $appointment)
    {
        $this->authorize('view', $appointment);
        
        $veterinariaId = Auth::user()->tenant_id;
        $clients = Cliente::where('veterinaria_id', $veterinariaId)->get();
        $mascots = Mascota::where('veterinaria_id', $veterinariaId)->get();
        $veterinarians = Veterinario::where('veterinaria_id', $veterinariaId)->where('activo', true)->get();
        $services = Servicio::where('veterinaria_id', $veterinariaId)->where('activo', true)->orderBy('nombre')->get();
        $products = Producto::where('veterinaria_id', $veterinariaId)->orderBy('nombre')->get();

        $appointment->load(['servicios', 'productos', 'mascotas', 'statusHistories.usuario']);

        return view('appointments.edit', compact('appointment', 'clients', 'mascots', 'veterinarians', 'services', 'products'));
    }

    public function update(Request $request, Cita $appointment)
    {
        $this->authorize('update', $appointment);
        
        $veterinariaId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'mascota_id' => 'nullable|exists:mascotas,id',
            'mascotas' => 'array',
            'mascotas.*' => 'integer|exists:mascotas,id',
            'veterinario_id' => 'required|exists:veterinarios,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,cancelada,finalizada',
            'servicios' => 'array',
            'servicios.*' => 'integer|exists:servicios,id',
            'servicios_cantidad' => 'array',
            'servicios_cantidad.*' => 'nullable|integer|min:1',
            'productos' => 'array',
            'productos.*' => 'integer|exists:productos,id',
            'productos_cantidad' => 'array',
            'productos_cantidad.*' => 'nullable|integer|min:1',
        ]);

        $mascotaIds = $this->resolveMascotas($request, $validated['cliente_id'], $veterinariaId);
        $validated['mascota_id'] = $mascotaIds[0] ?? null;

        $serviceIds = array_map('intval', $request->input('servicios', []));
        $productIds = array_map('intval', $request->input('productos', []));

        $servicios = Servicio::whereIn('id', $serviceIds)
            ->where('veterinaria_id', $veterinariaId)
            ->get()
            ->keyBy('id');

        if (count($serviceIds) !== $servicios->count()) {
            throw ValidationException::withMessages([
                'servicios' => 'Uno o mas servicios no pertenecen a tu veterinaria.',
            ]);
        }

        $existingProductos = $appointment->productos()->pluck('cita_producto.cantidad', 'productos.id')->toArray();
        $allProductIds = array_unique(array_merge($productIds, array_keys($existingProductos)));

        $productos = Producto::whereIn('id', $allProductIds)
            ->where('veterinaria_id', $veterinariaId)
            ->get()
            ->keyBy('id');

        if (count($allProductIds) !== $productos->count()) {
            throw ValidationException::withMessages([
                'productos' => 'Uno o mas productos no pertenecen a tu veterinaria.',
            ]);
        }

        $serviciosSync = [];
        foreach ($serviceIds as $serviceId) {
            $cantidad = (int) ($request->input("servicios_cantidad.$serviceId") ?? 1);
            $serviciosSync[$serviceId] = ['cantidad' => max(1, $cantidad)];
        }

        $productosSync = [];
        foreach ($productIds as $productId) {
            $cantidad = (int) ($request->input("productos_cantidad.$productId") ?? 1);
            $productosSync[$productId] = ['cantidad' => max(1, $cantidad)];
        }

        $previousStatus = $appointment->estado;
        $nextStatus = $validated['estado'];
        $validated = array_merge($validated, $this->statusTimestamps($nextStatus, $appointment));

        DB::transaction(function () use ($appointment, $validated, $serviciosSync, $productosSync, $productos, $existingProductos, $mascotaIds) {
            $appointment->update($validated);

            if (!empty($mascotaIds)) {
                $appointment->mascotas()->sync($mascotaIds);
            } else {
                $appointment->mascotas()->detach();
            }

            $appointment->servicios()->sync($serviciosSync);
            $appointment->productos()->sync($productosSync);

            foreach ($productosSync as $productId => $pivot) {
                $newCantidad = $pivot['cantidad'];
                $oldCantidad = $existingProductos[$productId] ?? 0;
                $delta = $newCantidad - $oldCantidad;

                if ($delta === 0) {
                    continue;
                }

                $producto = $productos->get($productId);

                if ($delta > 0) {
                    if ($producto->stock_actual < $delta) {
                        throw ValidationException::withMessages([
                            'productos' => "Stock insuficiente para {$producto->nombre}.",
                        ]);
                    }

                    $producto->decrement('stock_actual', $delta);

                    MovimientoStock::create([
                        'producto_id' => $productId,
                        'user_id' => Auth::id(),
                        'tipo' => 'salida',
                        'cantidad' => $delta,
                        'motivo' => 'Uso en cita (ajuste)',
                        'referencia' => "cita:{$appointment->id}",
                        'fecha' => now(),
                    ]);
                } else {
                    $producto->increment('stock_actual', abs($delta));

                    MovimientoStock::create([
                        'producto_id' => $productId,
                        'user_id' => Auth::id(),
                        'tipo' => 'entrada',
                        'cantidad' => abs($delta),
                        'motivo' => 'Devolucion por ajuste de cita',
                        'referencia' => "cita:{$appointment->id}",
                        'fecha' => now(),
                    ]);
                }
            }

            foreach ($existingProductos as $productId => $oldCantidad) {
                if (isset($productosSync[$productId])) {
                    continue;
                }

                $producto = $productos->get($productId);
                $producto->increment('stock_actual', $oldCantidad);

                MovimientoStock::create([
                    'producto_id' => $productId,
                    'user_id' => Auth::id(),
                    'tipo' => 'entrada',
                    'cantidad' => $oldCantidad,
                    'motivo' => 'Devolucion por ajuste de cita',
                    'referencia' => "cita:{$appointment->id}",
                    'fecha' => now(),
                ]);
            }
        });

        if ($previousStatus !== $nextStatus) {
            $this->recordStatusChange($appointment, $previousStatus, $nextStatus, 'Actualizacion manual');
            $this->sendStatusEmail($appointment, $nextStatus);
        }

        return redirect()->route('appointments.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Cita eliminada exitosamente.');
    }

    public function confirm(Cita $appointment)
    {
        $this->authorize('update', $appointment);

        $this->transitionStatus($appointment, 'confirmada', 'Confirmacion manual');

        return redirect()->route('appointments.index')->with('success', 'Cita confirmada.');
    }

    public function cancel(Cita $appointment)
    {
        $this->authorize('update', $appointment);

        $this->transitionStatus($appointment, 'cancelada', 'Cancelacion manual');

        return redirect()->route('appointments.index')->with('success', 'Cita cancelada.');
    }

    public function finalize(Cita $appointment)
    {
        $this->authorize('update', $appointment);

        $this->transitionStatus($appointment, 'finalizada', 'Finalizacion manual');

        return redirect()->route('appointments.index')->with('success', 'Cita finalizada.');
    }

    public function reminder(Cita $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->loadMissing(['cliente', 'mascotas', 'mascota']);
        $message = $appointment->buildWhatsappReminderMessage();

        $appointment->forceFill(['reminder_sent_at' => now()])->save();
        $this->sendReminderEmail($appointment);

        return redirect()
            ->route('appointments.index')
            ->with('whatsapp_message', $message)
            ->with('success', 'Recordatorio generado.');
    }

    private function resolveMascotas(Request $request, int $clienteId, int $veterinariaId): array
    {
        $mascotaIds = array_map('intval', $request->input('mascotas', []));
        $mascotaIds = array_values(array_unique(array_filter($mascotaIds)));

        if (empty($mascotaIds) && $request->filled('mascota_id')) {
            $mascotaIds = [(int) $request->input('mascota_id')];
        }

        if (!empty($mascotaIds)) {
            $count = Mascota::whereIn('id', $mascotaIds)
                ->where('cliente_id', $clienteId)
                ->where('veterinaria_id', $veterinariaId)
                ->count();

            if ($count !== count($mascotaIds)) {
                throw ValidationException::withMessages([
                    'mascotas' => 'Una o mas mascotas no pertenecen al cliente o veterinaria.',
                ]);
            }
        }

        return $mascotaIds;
    }

    private function statusTimestamps(string $status, ?Cita $appointment = null): array
    {
        $now = now();
        $timestamps = [];

        if ($status === 'confirmada' && (!$appointment || !$appointment->confirmed_at)) {
            $timestamps['confirmed_at'] = $now;
        }

        if ($status === 'cancelada' && (!$appointment || !$appointment->canceled_at)) {
            $timestamps['canceled_at'] = $now;
        }

        if ($status === 'finalizada' && (!$appointment || !$appointment->completed_at)) {
            $timestamps['completed_at'] = $now;
        }

        return $timestamps;
    }

    private function transitionStatus(Cita $appointment, string $status, string $note): void
    {
        $from = $appointment->estado;

        if ($from === $status) {
            return;
        }

        $payload = array_merge(['estado' => $status], $this->statusTimestamps($status, $appointment));
        $appointment->update($payload);

        $this->recordStatusChange($appointment, $from, $status, $note);
        $this->sendStatusEmail($appointment, $status);
    }

    private function recordStatusChange(Cita $appointment, ?string $from, string $to, ?string $note = null): void
    {
        CitaStatusHistory::create([
            'cita_id' => $appointment->id,
            'from_status' => $from,
            'to_status' => $to,
            'changed_by' => Auth::id(),
            'note' => $note,
        ]);
    }

    private function sendStatusEmail(Cita $appointment, string $status): void
    {
        $appointment->loadMissing('cliente');

        if (!$appointment->cliente || !$appointment->cliente->correo) {
            return;
        }

        if ($status === 'confirmada') {
            Mail::to($appointment->cliente->correo)->send(new AppointmentConfirmed($appointment));
        }

        if ($status === 'cancelada') {
            Mail::to($appointment->cliente->correo)->send(new AppointmentCanceled($appointment));
        }
    }

    private function sendReminderEmail(Cita $appointment): void
    {
        $appointment->loadMissing('cliente');

        if (!$appointment->cliente || !$appointment->cliente->correo) {
            return;
        }

        Mail::to($appointment->cliente->correo)->send(new AppointmentReminder($appointment));
    }

    private function cancelExpiredUnconfirmedAppointments(int $veterinariaId): void
    {
        $now = now();
        
        // Buscar citas que han pasado su hora sin ser confirmadas
        $expiredAppointments = Cita::where('veterinaria_id', $veterinariaId)
            ->where('estado', 'pendiente')
            ->whereNull('confirmed_at')
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    // Citas de días anteriores
                    $q->where('fecha', '<', $now->toDateString());
                })
                ->orWhere(function ($q) use ($now) {
                    // Citas de hoy que ya pasaron
                    $q->whereDate('fecha', $now->toDateString())
                      ->whereTime('hora', '<=', $now->format('H:i:s'));
                });
            })
            ->get();

        foreach ($expiredAppointments as $appointment) {
            $this->transitionStatus($appointment, 'cancelada', 'Cancelacion automatica por tiempo expirado');
        }
    }
}