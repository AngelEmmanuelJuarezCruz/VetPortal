<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\MovimientoStock;
use App\Exports\CitasReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $veterinariaId = Auth::user()->tenant_id;

        $reportes = [
            'citas' => [
                'icon' => 'fas fa-calendar-check',
                'title' => 'Reporte de Citas',
                'description' => 'Citas realizadas en un rango de fechas',
                'route' => 'reportes.citas',
            ],
            'inventario' => [
                'icon' => 'fas fa-boxes',
                'title' => 'Reporte de Inventario',
                'description' => 'Estado actual del stock de productos',
                'route' => 'reportes.inventario',
            ],
            'consumo' => [
                'icon' => 'fas fa-chart-line',
                'title' => 'Reporte de Consumo',
                'description' => 'Consumo de productos en citas',
                'route' => 'reportes.consumo',
            ],
            'servicios' => [
                'icon' => 'fas fa-stethoscope',
                'title' => 'Reporte de Servicios',
                'description' => 'Servicios vendidos e ingresos',
                'route' => 'reportes.servicios',
            ],
            'ingresos' => [
                'icon' => 'fas fa-dollar-sign',
                'title' => 'Reporte de Ingresos',
                'description' => 'Ingresos mensuales por servicios',
                'route' => 'reportes.ingresos',
            ],
            'movimientos' => [
                'icon' => 'fas fa-exchange-alt',
                'title' => 'Movimientos de Stock',
                'description' => 'Historial de entradas y salidas',
                'route' => 'reportes.movimientos',
            ],
        ];

        return view('reportes.index', compact('reportes'));
    }

    public function inventario()
    {
        $veterinariaId = Auth::user()->tenant_id;

        $productos = Producto::where('veterinaria_id', $veterinariaId)
            ->with('movimientosStock')
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        $resumen = [
            'total_productos' => $productos->count(),
            'total_valor' => $productos->sum(fn($p) => $p->stock_actual * $p->precio_venta),
            'bajo_stock' => $productos->where('stock_actual', '<=', $productos->min('stock_minimo'))->count(),
            'por_caducar' => $productos
                ->filter(fn($p) => $p->fecha_caducidad && $p->fecha_caducidad->isBetween(now(), now()->addDays(30)))
                ->count(),
        ];

        return view('reportes.inventario', compact('productos', 'resumen'));
    }

    public function consumo(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);

        $consumo = MovimientoStock::with('producto')
            ->whereHas('producto', fn($q) => $q->where('veterinaria_id', $veterinariaId))
            ->where('tipo', 'salida')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $ano)
            ->get()
            ->groupBy('producto_id')
            ->map(function ($movimientos) {
                $producto = $movimientos->first()->producto;
                return [
                    'producto' => $producto,
                    'cantidad_total' => $movimientos->sum('cantidad'),
                    'movimientos' => $movimientos->count(),
                    'valor_consumido' => $movimientos->sum(fn($m) => $m->cantidad * $producto->precio_venta),
                ];
            })
            ->sortByDesc('cantidad_total');

        $total_consumido = $consumo->sum('cantidad_total');
        $valor_total = $consumo->sum('valor_consumido');

        return view('reportes.consumo', compact('consumo', 'total_consumido', 'valor_total', 'mes', 'ano'));
    }

    public function servicios(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);

        $servicios_vendidos = Cita::where('veterinaria_id', $veterinariaId)
            ->with('servicios')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $ano)
            ->get()
            ->flatMap(fn($cita) => $cita->servicios)
            ->groupBy('id')
            ->map(function ($servicios) {
                $servicio = $servicios->first();
                return [
                    'servicio' => $servicio,
                    'cantidad' => $servicios->count(),
                    'ingresos' => $servicios->sum(fn($s) => $s->pivot->cantidad * $s->precio),
                ];
            })
            ->sortByDesc('cantidad');

        $total_servicios = $servicios_vendidos->count();
        $ingresos_totales = $servicios_vendidos->sum('ingresos');

        return view('reportes.servicios', compact('servicios_vendidos', 'total_servicios', 'ingresos_totales', 'mes', 'ano'));
    }

    public function ingresos(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;
        $ano = $request->input('ano', now()->year);

        $ingresos_mensuales = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $monto = Cita::where('veterinaria_id', $veterinariaId)
                ->with('servicios')
                ->whereMonth('fecha', $mes)
                ->whereYear('fecha', $ano)
                ->get()
                ->flatMap(fn($cita) => $cita->servicios)
                ->sum(fn($s) => $s->pivot->cantidad * $s->precio);

            $ingresos_mensuales[\Illuminate\Support\Carbon::createFromDate($ano, $mes, 1)->format('M')] = $monto;
        }

        $ingresos_totales = array_sum($ingresos_mensuales);
        $promedio_mensual = $ingresos_totales / count($ingresos_mensuales);

        return view('reportes.ingresos', compact('ingresos_mensuales', 'ingresos_totales', 'promedio_mensual', 'ano'));
    }

    public function movimientos(Request $request)
    {
        $veterinariaId = Auth::user()->tenant_id;

        $movimientos = MovimientoStock::with(['producto', 'user'])
            ->whereHas('producto', fn($q) => $q->where('veterinaria_id', $veterinariaId))
            ->orderByDesc('fecha')
            ->paginate(20);

        return view('reportes.movimientos', compact('movimientos'));
    }

    public function citas(Request $request)
    {
        $this->validateReportDateRange($request);

        $range = $this->resolveReportDateRange($request);
        $citas = $this->buildCitasReportQuery($range['desde'], $range['hasta'])->get();
        $resumen = $this->buildCitasResumen($citas);

        return view('reportes.citas', [
            'citas' => $citas,
            'resumen' => $resumen,
            'desde' => $range['desde_input'],
            'hasta' => $range['hasta_input'],
        ]);
    }

    public function exportCitasExcel(Request $request)
    {
        $this->validateReportDateRange($request);

        $range = $this->resolveReportDateRange($request);
        $citas = $this->buildCitasReportQuery($range['desde'], $range['hasta'])->get();
        $fileName = 'reporte-citas-' . $range['desde_input'] . '-a-' . $range['hasta_input'] . '.xlsx';

        return Excel::download(new CitasReportExport($citas), $fileName);
    }

    public function exportCitasPdf(Request $request)
    {
        $this->validateReportDateRange($request);

        $range = $this->resolveReportDateRange($request);
        $citas = $this->buildCitasReportQuery($range['desde'], $range['hasta'])->get();
        $resumen = $this->buildCitasResumen($citas);
        $fileName = 'reporte-citas-' . $range['desde_input'] . '-a-' . $range['hasta_input'] . '.pdf';

        $pdf = Pdf::loadView('reportes.citas-pdf', [
            'citas' => $citas,
            'resumen' => $resumen,
            'desde' => $range['desde_input'],
            'hasta' => $range['hasta_input'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function validateReportDateRange(Request $request): void
    {
        $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date'],
        ]);
    }

    private function resolveReportDateRange(Request $request): array
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $desdeDate = $desde ? Carbon::parse($desde)->startOfDay() : now()->subDays(30)->startOfDay();
        $hastaDate = $hasta ? Carbon::parse($hasta)->endOfDay() : now()->endOfDay();

        if ($desdeDate->gt($hastaDate)) {
            [$desdeDate, $hastaDate] = [$hastaDate->copy()->startOfDay(), $desdeDate->copy()->endOfDay()];
        }

        return [
            'desde' => $desdeDate,
            'hasta' => $hastaDate,
            'desde_input' => $desdeDate->toDateString(),
            'hasta_input' => $hastaDate->toDateString(),
        ];
    }

    private function buildCitasReportQuery(Carbon $desde, Carbon $hasta)
    {
        $veterinariaId = Auth::user()->tenant_id;

        return Cita::where('veterinaria_id', $veterinariaId)
            ->with(['cliente', 'mascota', 'mascotas', 'veterinario', 'servicios', 'productos', 'agendadoPor'])
            ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    private function buildCitasResumen($citas): array
    {
        $totalServicios = $citas->sum(function ($cita) {
            return $cita->servicios->sum(fn($s) => $s->pivot->cantidad * $s->precio);
        });

        $totalProductos = $citas->sum(function ($cita) {
            return $cita->productos->sum(fn($p) => $p->pivot->cantidad * $p->precio_venta);
        });

        return [
            'total_citas' => $citas->count(),
            'total_servicios' => $totalServicios,
            'total_productos' => $totalProductos,
            'total_general' => $totalServicios + $totalProductos,
        ];
    }
}
