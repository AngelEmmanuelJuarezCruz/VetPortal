<?php

namespace App\Http\Controllers;

use App\Models\ClinicalHistory;
use App\Models\Mascota;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicalHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Mascota $mascota)
    {
        // Eager load relationships and paginate
        $histories = $mascota->clinicalHistories()->with('veterinarian')->latest('visit_date')->paginate(10);

        return view('clinical-histories.index', compact('mascota', 'histories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Mascota $mascota)
    {
        $veterinarians = Veterinario::where('veterinaria_id', Auth::user()->tenant_id)->get();
        return view('clinical-histories.create', compact('mascota', 'veterinarians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Mascota $mascota)
    {
        $request->validate([
            'visit_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
            'veterinarian_id' => 'nullable|exists:veterinarios,id',
        ]);

        $mascota->clinicalHistories()->create($request->all());

        return redirect()->route('pets.clinical-histories.index', $mascota)
            ->with('success', 'Historial clínico añadido con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicalHistory $clinicalHistory)
    {
        $mascota = $clinicalHistory->pet;
        $clinicalHistory->delete();

        return redirect()->route('mascotas.clinical-histories.index', $mascota)
            ->with('success', 'Entrada del historial eliminada con éxito.');
    }
}
