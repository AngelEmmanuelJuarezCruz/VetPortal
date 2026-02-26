<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $veterinariaId = Auth::user()->tenant_id;
        $pets = Mascota::where('veterinaria_id', $veterinariaId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                        ->orWhere('especie', 'like', "%$search%")
                        ->orWhere('raza', 'like', "%$search%")
                        ->orWhereHas('cliente', function ($clienteQuery) use ($search) {
                            $clienteQuery->where('nombre', 'like', "%$search%")
                                ->orWhere('apellido', 'like', "%$search%");
                        });
                });
            })
            ->with('cliente')
            ->paginate(10)
            ->withQueryString();

        return view('pets.index', compact('pets', 'search'));
    }

    public function create(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;
        $clients = Cliente::where('veterinaria_id', $veterinariaId)
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
        $selectedClientId = $request->input('cliente_id');

        return view('pets.create', compact('clients', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $veterinariaId = Auth::user()->tenant_id;
        $client = Cliente::where('id', $validated['cliente_id'])
            ->where('veterinaria_id', $veterinariaId)
            ->firstOrFail();

        $validated['cliente_id'] = $client->id;
        $validated['veterinaria_id'] = $veterinariaId;

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pets', 'public');
            $validated['foto'] = $path;
        }

        Mascota::create($validated);

        return redirect()->route('pets.index')->with('success', 'Mascota registrada exitosamente.');
    }

    public function edit(Mascota $pet)
    {
        $veterinariaId = Auth::user()->tenant_id;
        if ($pet->veterinaria_id !== $veterinariaId) {
            abort(403, 'Unauthorized action.');
        }

        $clients = Cliente::where('veterinaria_id', $veterinariaId)
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view('pets.edit', ['pet' => $pet, 'clients' => $clients]);
    }

    public function update(Request $request, Mascota $pet)
    {
        $veterinariaId = Auth::user()->tenant_id;
        if ($pet->veterinaria_id !== $veterinariaId) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = Cliente::where('id', $validated['cliente_id'])
            ->where('veterinaria_id', $veterinariaId)
            ->firstOrFail();

        $updateData = [
            'cliente_id' => $client->id,
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'] ?? null,
            'color' => $validated['color'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
        ];

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pets', 'public');
            $updateData['foto'] = $path;
        }

        $pet->update($updateData);

        return redirect()->route('pets.index')->with('success', 'Mascota actualizada exitosamente.');
    }

    public function destroy(Mascota $pet)
    {
        $veterinariaId = Auth::user()->tenant_id;
        if ($pet->veterinaria_id !== $veterinariaId) {
            abort(403, 'Unauthorized action.');
        }

        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Mascota eliminada exitosamente.');
    }
}