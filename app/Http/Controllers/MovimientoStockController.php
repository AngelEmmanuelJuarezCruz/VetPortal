<?php

namespace App\Http\Controllers;

use App\Models\MovimientoStock;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MovimientoStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $veterinariaId = Auth::user()->tenant_id;

        $movimientos = MovimientoStock::with(['producto', 'user'])
            ->whereHas('producto', function ($query) use ($veterinariaId, $search) {
                $query->where('veterinaria_id', $veterinariaId)
                    ->when($search, function ($productoQuery, $search) {
                        return $productoQuery->where(function ($q) use ($search) {
                            $q->where('nombre', 'like', "%$search%")
                                ->orWhere('categoria', 'like', "%$search%");
                        });
                    });
            })
            ->orderByDesc('fecha')
            ->paginate(15);

        return view('movimientos-stock.index', compact('movimientos', 'search'));
    }

    public function create()
    {
        $veterinariaId = Auth::user()->tenant_id;
        $productos = Producto::where('veterinaria_id', $veterinariaId)
            ->orderBy('nombre')
            ->get();

        return view('movimientos-stock.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);

        $veterinariaId = Auth::user()->tenant_id;
        $producto = Producto::where('id', $validated['producto_id'])
            ->where('veterinaria_id', $veterinariaId)
            ->firstOrFail();

        DB::transaction(function () use ($validated, $producto) {
            if ($validated['tipo'] === 'salida' && $producto->stock_actual < $validated['cantidad']) {
                throw ValidationException::withMessages([
                    'cantidad' => 'Stock insuficiente para la salida seleccionada.',
                ]);
            }

            if ($validated['tipo'] === 'entrada') {
                $producto->increment('stock_actual', $validated['cantidad']);
            } else {
                $producto->decrement('stock_actual', $validated['cantidad']);
            }

            MovimientoStock::create([
                'producto_id' => $producto->id,
                'user_id' => Auth::id(),
                'tipo' => $validated['tipo'],
                'cantidad' => $validated['cantidad'],
                'motivo' => $validated['motivo'],
                'referencia' => $validated['referencia'] ?? null,
                'fecha' => $validated['fecha'] ?? now(),
            ]);
        });

        return redirect()->route('movimientos-stock.index')->with('success', 'Movimiento registrado exitosamente.');
    }
}
