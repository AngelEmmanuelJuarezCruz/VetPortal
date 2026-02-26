<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $veterinariaId = Auth::user()->tenant_id;

        $servicios = Servicio::where('veterinaria_id', $veterinariaId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                        ->orWhere('categoria', 'like', "%$search%");
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('servicios.index', compact('servicios', 'search'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:salud,estetica,cirugia,bienestar',
            'descripcion' => 'nullable|string',
            'duracion_estimada' => 'nullable|integer|min:1',
            'precio' => 'nullable|numeric|min:0',
            'activo' => 'required|boolean',
        ]);

        $veterinariaId = Auth::user()->tenant_id;

        $validated['veterinaria_id'] = $veterinariaId;
        $validated['tenant_id'] = $veterinariaId;

        Servicio::create($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function edit(Servicio $servicio)
    {
        if ($servicio->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        if ($servicio->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:salud,estetica,cirugia,bienestar',
            'descripcion' => 'nullable|string',
            'duracion_estimada' => 'nullable|integer|min:1',
            'precio' => 'nullable|numeric|min:0',
            'activo' => 'required|boolean',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}
