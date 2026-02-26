<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterBy = $request->input('filter_by');
        $filterValue = $request->input('filter_value');
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(5, min($perPage, 50));
        $veterinariaId = Auth::user()->tenant_id;

        $clients = Cliente::where('veterinaria_id', $veterinariaId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                        ->orWhere('apellido', 'like', "%$search%")
                        ->orWhere('telefono', 'like', "%$search%")
                        ->orWhere('correo', 'like', "%$search%")
                        ->orWhere('uuid', 'like', "%$search%");
                });
            })
            ->when($filterBy && $filterValue, function ($query) use ($filterBy, $filterValue) {
                $allowed = ['nombre', 'apellido', 'telefono', 'correo', 'uuid'];
                if (in_array($filterBy, $allowed, true)) {
                    $query->where($filterBy, 'like', "%$filterValue%");
                }
            })
            ->withCount('mascotas')
            ->paginate($perPage)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search', 'filterBy', 'filterValue', 'perPage'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|unique:clientes,telefono,NULL,id,veterinaria_id,' . Auth::user()->tenant_id,
            'correo' => 'required|email|unique:clientes,correo,NULL,id,veterinaria_id,' . Auth::user()->tenant_id,
            'pets' => 'nullable|array',
            'pets.*.nombre' => 'required|string|max:255',
            'pets.*.especie' => 'required|string|max:255',
            'pets.*.raza' => 'nullable|string|max:255',
            'pets.*.color' => 'nullable|string|max:255',
            'pets.*.descripcion' => 'nullable|string',
            'pets.*.fecha_nacimiento' => 'nullable|date',
        ]);

        $veterinariaId = Auth::user()->tenant_id;
        $pets = $validated['pets'] ?? [];

        DB::transaction(function () use ($validated, $pets, $veterinariaId) {
            $client = Cliente::create([
                'uuid' => (string) Str::uuid(),
                'veterinaria_id' => $veterinariaId,
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono'],
                'correo' => $validated['correo'],
            ]);

            foreach ($pets as $pet) {
                $client->mascotas()->create([
                    'veterinaria_id' => $veterinariaId,
                    'nombre' => $pet['nombre'],
                    'especie' => $pet['especie'],
                    'raza' => $pet['raza'] ?? null,
                    'color' => $pet['color'] ?? null,
                    'descripcion' => $pet['descripcion'] ?? null,
                    'fecha_nacimiento' => $pet['fecha_nacimiento'] ?? null,
                ]);
            }
        });

        return redirect()->route('clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $client)
    {
        // Ensure the client belongs to the authenticated user's vet clinic
        if ($client->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Cliente $client)
    {
        // Ensure the client belongs to the authenticated user's vet clinic
        if ($client->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|unique:clientes,telefono,' . $client->id . ',id,veterinaria_id,' . Auth::user()->tenant_id,
            'correo' => 'required|email|unique:clientes,correo,' . $client->id . ',id,veterinaria_id,' . Auth::user()->tenant_id,
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $client)
    {
        // Ensure the client belongs to the authenticated user's vet clinic
        if ($client->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}