<p>Hola {{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }},</p>

<p>Tu cita ha sido cancelada.</p>

<p><strong>Fecha:</strong> {{ $appointment->fecha->format('d/m/Y') }}<br>
<strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }}<br>
<strong>Motivo:</strong> {{ $appointment->motivo }}</p>

<p>Si necesitas reagendar, por favor contactanos.</p>
