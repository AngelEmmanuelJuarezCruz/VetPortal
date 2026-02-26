<?php

namespace App\Exports;

use App\Models\Cita;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CitasReportExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $citas;

    public function __construct(Collection $citas)
    {
        $this->citas = $citas;
    }

    public function collection(): Collection
    {
        return $this->citas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Hora',
            'Cliente',
            'Mascotas',
            'Veterinario',
            'Motivo',
            'Estado',
            'Servicios',
            'Productos',
            'Total servicios',
            'Total productos',
            'Total general',
        ];
    }

    /**
     * @param Cita $cita
     */
    public function map($cita): array
    {
        $cliente = $cita->cliente ? trim($cita->cliente->nombre . ' ' . $cita->cliente->apellido) : 'Sin cliente';
        $mascotas = $cita->mascotas->pluck('nombre')->filter()->values();
        if ($mascotas->isEmpty() && $cita->mascota) {
            $mascotas = collect([$cita->mascota->nombre]);
        }
        $mascotasLabel = $mascotas->isEmpty() ? 'Sin mascota' : $mascotas->join(', ');
        $veterinario = $cita->veterinario ? $cita->veterinario->nombre : 'Sin veterinario';
        $serviciosLabel = $cita->servicios->map(fn($s) => $s->nombre . ' x' . $s->pivot->cantidad)->filter()->join(', ');
        $productosLabel = $cita->productos->map(fn($p) => $p->nombre . ' x' . $p->pivot->cantidad)->filter()->join(', ');
        $totalServicios = $cita->servicios->sum(fn($s) => $s->pivot->cantidad * $s->precio);
        $totalProductos = $cita->productos->sum(fn($p) => $p->pivot->cantidad * $p->precio_venta);

        return [
            $cita->id,
            $cita->fecha->format('Y-m-d'),
            \Carbon\Carbon::parse($cita->hora)->format('H:i'),
            $cliente,
            $mascotasLabel,
            $veterinario,
            $cita->motivo ?? '-',
            $cita->estado,
            $serviciosLabel ?: '-',
            $productosLabel ?: '-',
            number_format($totalServicios, 2, '.', ''),
            number_format($totalProductos, 2, '.', ''),
            number_format($totalServicios + $totalProductos, 2, '.', ''),
        ];
    }
}
