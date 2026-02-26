<?php

use App\Exports\CitasReportExport;
use App\Models\Cita;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

$app = app();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::whereNotNull('tenant_id')->first();

if (!$user) {
    echo "No hay usuario con tenant.\n";
    exit(1);
}

$desde = now()->subDays(60)->startOfDay();
$hasta = now()->endOfDay();

$citas = Cita::where('veterinaria_id', $user->tenant_id)
    ->with(['cliente', 'mascota', 'mascotas', 'veterinario', 'servicios', 'productos', 'agendadoPor'])
    ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
    ->orderBy('fecha')
    ->orderBy('hora')
    ->get();

$totalServicios = $citas->sum(fn($cita) => $cita->servicios->sum(fn($s) => $s->pivot->cantidad * $s->precio));
$totalProductos = $citas->sum(fn($cita) => $cita->productos->sum(fn($p) => $p->pivot->cantidad * $p->precio_venta));

$resumen = [
    'total_citas' => $citas->count(),
    'total_servicios' => $totalServicios,
    'total_productos' => $totalProductos,
    'total_general' => $totalServicios + $totalProductos,
];

$reportsDir = storage_path('app/reports');
if (!is_dir($reportsDir)) {
    mkdir($reportsDir, 0755, true);
}

$desdeLabel = $desde->toDateString();
$hastaLabel = $hasta->toDateString();

$excelPath = "reports/citas-{$desdeLabel}-a-{$hastaLabel}.xlsx";
Excel::store(new CitasReportExport($citas), $excelPath);

$pdfPath = "reports/citas-{$desdeLabel}-a-{$hastaLabel}.pdf";
Pdf::loadView('reportes.citas-pdf', [
    'citas' => $citas,
    'resumen' => $resumen,
    'desde' => $desdeLabel,
    'hasta' => $hastaLabel,
])->setPaper('a4', 'landscape')->save(storage_path('app/' . $pdfPath));

echo "Export OK\n";
echo "Excel: " . storage_path('app/' . $excelPath) . "\n";
echo "PDF: " . storage_path('app/' . $pdfPath) . "\n";
