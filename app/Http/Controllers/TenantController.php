<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Veterinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:veterinarias,correo|unique:tenants,email',
            'telefono' => 'required|string|unique:veterinarias,telefono|unique:tenants,phone',
            'direccion' => 'nullable|string',
            'tema' => 'nullable|in:claro,oscuro',
        ]);

        $uuid = (string) Str::uuid();

        DB::transaction(function () use ($validated, $uuid) {
            // Create in tenants table
            $tenant = Tenant::create([
                'uuid' => $uuid,
                'name' => $validated['nombre'],
                'email' => $validated['correo'],
                'phone' => $validated['telefono'],
                'address' => $validated['direccion'] ?? null,
                'theme' => $validated['tema'] === 'oscuro' ? 'dark' : 'light',
            ]);

            // Create in veterinarias table  
            Veterinaria::create([
                'uuid' => $uuid,
                'nombre' => $validated['nombre'],
                'correo' => $validated['correo'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'] ?? null,
                'tema' => $validated['tema'] ?? 'claro',
            ]);

            // Update the user's tenant_id to reference tenants table
            Auth::user()->update(['tenant_id' => $tenant->id]);
        });

        return redirect()->route('dashboard')->with('success', 'Clínica veterinaria creada exitosamente.');
    }

    /**
     * Display the specified tenant.
     */
    public function show(Veterinaria $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Veterinaria $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(Request $request, Veterinaria $tenant)
    {
        // Ensure user owns the tenant
        if (Auth::user()->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:veterinarias,correo,' . $tenant->id,
            'telefono' => 'required|string|unique:veterinarias,telefono,' . $tenant->id,
            'direccion' => 'nullable|string',
            'tema' => 'nullable|in:claro,oscuro',
        ]);

        $tenant->update([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'] ?? null,
            'tema' => $validated['tema'] ?? $tenant->tema,
        ]);

        return redirect()->route('tenants.show', $tenant)->with('success', 'Clínica actualizada exitosamente.');
    }

    /**
     * Remove the specified tenant from storage.
     */
    public function destroy(Veterinaria $tenant)
    {
        // Ensure user owns the tenant
        if (Auth::user()->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized action.');
        }

        $tenant->delete();

        return redirect('/')->with('success', 'Clínica eliminada exitosamente.');
    }
}

