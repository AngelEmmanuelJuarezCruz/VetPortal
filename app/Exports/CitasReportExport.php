<?php

namespace App\Exports;

use App\Models\Cita;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CitasReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
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
            $cita->fecha->format('d/m/Y'),
            \Carbon\Carbon::parse($cita->hora)->format('h:i A'),
            $cliente,
            $mascotasLabel,
            $veterinario,
            $cita->motivo ?? '-',
            $cita->estado,
            $serviciosLabel ?: '-',
            $productosLabel ?: '-',
            $totalServicios,
            $totalProductos,
            $totalServicios + $totalProductos,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $this->citas->count() + 1;

        // Estilo para el header
        $sheet->getStyle('A1:M1')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1F2937'));
        $sheet->getStyle('A1:M1')->getFill()->setFillType('solid')->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('F3F4F6'));
        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        
        // Bordes para header
        $sheet->getStyle('A1:M1')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A1:M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A1:M1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A1:M1')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));

        // Estilo para las filas de datos
        $sheet->getStyle('A2:M' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle('A2:M' . $highestRow)->getFont()->setSize(10);

        // Bordes para datos
        $sheet->getStyle('A2:M' . $highestRow)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A2:M' . $highestRow)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A2:M' . $highestRow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A2:M' . $highestRow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));

        // Alineación a la derecha para columnas de dinero
        $sheet->getStyle('K2:M' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('K1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Altura del row del header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '0',           // ID
            'B' => 'dd/mm/yyyy',  // Fecha
            'K' => '$#,##0.00',   // Total servicios
            'L' => '$#,##0.00',   // Total productos
            'M' => '$#,##0.00',   // Total general
        ];
    }
}
