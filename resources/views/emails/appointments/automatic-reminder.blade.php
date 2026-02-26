<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 8px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin-top: 20px; border-radius: 8px; }
        .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #667eea; }
        .label { font-weight: bold; color: #667eea; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 30px; }
        .days-badge { display: inline-block; background: #ff6b6b; color: white; padding: 10px 15px; border-radius: 5px; font-size: 18px; font-weight: bold; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Recordatorio de tu cita!</h1>
            <p>Tu cita se aproxima</p>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</strong>,</p>

            <p>Queremos recordarte que tienes una cita programada próximamente:</p>

            <div style="text-align: center;">
                <div class="days-badge">EN {{ $daysUntil }} {{ $daysUntil == 1 ? 'DÍA' : 'DÍAS' }}</div>
            </div>

            <div class="details">
                <p>
                    <span class="label">Fecha:</span> {{ $appointment->fecha->format('d/m/Y') }}<br>
                    <span class="label">Hora:</span> {{ \Carbon\Carbon::parse($appointment->hora)->format('H:i') }}<br>
                    <span class="label">Motivo:</span> {{ $appointment->motivo }}
                </p>

                @php
                    $mascotas = $appointment->mascotas->pluck('nombre')->filter()->values();
                    if ($mascotas->isEmpty() && $appointment->mascota) {
                        $mascotas = collect([$appointment->mascota->nombre]);
                    }
                @endphp

                <p>
                    <span class="label">Mascota(s):</span> {{ $mascotas->isEmpty() ? 'Sin mascota' : $mascotas->join(', ') }}
                </p>
            </div>

            <p>Por favor, <strong>confirma tu asistencia</strong> o avísanos si necesitas reprogramar.</p>

            <p style="margin-top: 30px;">¡Te esperamos en nuestra clínica!</p>

            <p>
                Saludos,<br>
                <strong>Equipo de {{ $appointment->veterinaria->nombre ?? 'Clínica Veterinaria' }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático. Por favor, no respondas a este correo.</p>
            <p>Si tienes dudas, contacta con nuestra clínica directamente.</p>
        </div>
    </div>
</body>
</html>
