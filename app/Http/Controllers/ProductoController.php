<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $veterinariaId = Auth::user()->tenant_id;

        $productos = Producto::where('veterinaria_id', $veterinariaId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                        ->orWhere('categoria', 'like', "%$search%")
                        ->orWhere('tipo', 'like', "%$search%")
                        ->orWhere('proveedor', 'like', "%$search%");
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('productos.index', compact('productos', 'search'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:medicamento,articulo,alimento',
            'categoria' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'fecha_caducidad' => 'nullable|date',
        ]);

        $veterinariaId = Auth::user()->tenant_id;

        $validated['uuid'] = (string) Str::uuid();
        $validated['veterinaria_id'] = $veterinariaId;
        $validated['tenant_id'] = $veterinariaId;
        $validated['stock_actual'] = $validated['stock_actual'] ?? 0;
        $validated['stock_minimo'] = $validated['stock_minimo'] ?? 0;

        Producto::create($validated);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        if ($producto->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        if ($producto->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:medicamento,articulo,alimento',
            'categoria' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'fecha_caducidad' => 'nullable|date',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->veterinaria_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
