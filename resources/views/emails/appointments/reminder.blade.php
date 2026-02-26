<p>Hola {{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }},</p>

<p>Este es un recordatorio de tu cita.</p>

<p><strong>Fecha:</strong> {{ $appointment->fecha->format('d/m/Y') }}<br>
<strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }}<br>
<strong>Motivo:</strong> {{ $appointment->motivo }}</p>

@php
    $mascotas = $appointment->mascotas->pluck('nombre')->filter()->values();
    if ($mascotas->isEmpty() && $appointment->mascota) {
        $mascotas = collect([$appointment->mascota->nombre]);
    }
@endphp

<p><strong>Mascotas:</strong> {{ $mascotas->isEmpty() ? 'Sin mascota' : $mascotas->join(', ') }}</p>

<p>Te esperamos.</p>
