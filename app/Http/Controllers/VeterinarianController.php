<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeterinarianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $veterinarians = Veterinario::where('veterinaria_id', Auth::user()->tenant_id)
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', "%$search%")
                             ->orWhere('especialidad', 'like', "%$search%")
                             ->orWhere('telefono', 'like', "%$search%");
            })
            ->paginate(10);

        return view('veterinarians.index', compact('veterinarians', 'search'));
    }

    public function create()
    {
        return view('veterinarians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'required|string|unique:veterinarios,telefono,NULL,id,veterinaria_id,' . Auth::user()->tenant_id,
            'correo' => 'nullable|email|unique:veterinarios,correo,NULL,id,veterinaria_id,' . Auth::user()->tenant_id,
            'activo' => 'required|boolean',
        ]);

        $validated['veterinaria_id'] = Auth::user()->tenant_id;

        Veterinario::create($validated);

        return redirect()->route('veterinarians.index')->with('success', 'Veterinario registrado exitosamente.');
    }

    public function edit(Veterinario $veterinario)
    {
        // Ensure the veterinarian belongs to the authenticated user's vet clinic
        if ($veterinario->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
        return view('veterinarians.edit', compact('veterinario'));
    }

    public function update(Request $request, Veterinario $veterinario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'required|string|unique:veterinarios,telefono,' . $veterinario->id . ',id,veterinaria_id,' . Auth::user()->tenant_id,
            'correo' => 'nullable|email|unique:veterinarios,correo,' . $veterinario->id . ',id,veterinaria_id,' . Auth::user()->tenant_id,
            'activo' => 'required|boolean',
        ]);

        $veterinario->update($validated);

        return redirect()->route('veterinarians.index')->with('success', 'Veterinario actualizado exitosamente.');
    }

    public function destroy(Veterinario $veterinario)
    {
        $veterinario->delete();

        return redirect()->route('veterinarians.index')->with('success', 'Veterinario eliminado exitosamente.');
    }
}