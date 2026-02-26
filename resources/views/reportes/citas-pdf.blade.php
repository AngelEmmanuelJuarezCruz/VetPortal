<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Citas</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 8px 0; }
        .meta { margin-bottom: 12px; color: #4b5563; }
        .summary { margin-bottom: 12px; }
        .summary div { display: inline-block; margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; font-size: 11px; text-transform: uppercase; }
        .money { text-align: right; white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Reporte de Citas</h1>
    <div class="meta">Rango: {{ $desde }} a {{ $hasta }}</div>

    <div class="summary">
        <div>Total citas: {{ $resumen['total_citas'] }}</div>
        <div>Total servicios: ${{ number_format($resumen['total_servicios'], 2) }}</div>
        <div>Total productos: ${{ number_format($resumen['total_productos'], 2) }}</div>
        <div>Total general: ${{ number_format($resumen['total_general'], 2) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Mascotas</th>
                <th>Veterinario</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Servicios</th>
                <th>Productos</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($citas as $cita)
                @php
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
                    $totalGeneral = $totalServicios + $totalProductos;
                @endphp
                <tr>
                    <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</td>
                    <td>{{ $cliente }}</td>
                    <td>{{ $mascotasLabel }}</td>
                    <td>{{ $veterinario }}</td>
                    <td>{{ $cita->motivo ?? '-' }}</td>
                    <td>{{ ucfirst($cita->estado) }}</td>
                    <td>{{ $serviciosLabel ?: '-' }}</td>
                    <td>{{ $productosLabel ?: '-' }}</td>
                    <td class="money">${{ number_format($totalGeneral, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
