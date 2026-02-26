<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Reporte de Citas</h1>
                        <p class="text-sm text-gray-600">Rango: {{ $desde }} a {{ $hasta }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('reportes.citas.excel', ['desde' => $desde, 'hasta' => $hasta]) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">Exportar Excel</a>
                        <a href="{{ route('reportes.citas.pdf', ['desde' => $desde, 'hasta' => $hasta]) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">Exportar PDF</a>
                        <a href="{{ route('reportes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:text-gray-900">Volver</a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="desde">Desde</label>
                        <input type="date" id="desde" name="desde" value="{{ $desde }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="hasta">Hasta</label>
                        <input type="date" id="hasta" name="hasta" value="{{ $hasta }}" class="px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">Filtrar</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total citas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $resumen['total_citas'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total servicios</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($resumen['total_servicios'], 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total productos</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($resumen['total_productos'], 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total general</p>
                    <p class="text-2xl font-bold text-green-700">${{ number_format($resumen['total_general'], 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mascotas</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veterinario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicios</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($citas as $cita)
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
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $cita->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $cliente }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $mascotasLabel }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $veterinario }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $cita->motivo ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($cita->estado) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $serviciosLabel ?: '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $productosLabel ?: '-' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-700">${{ number_format($totalGeneral, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500">No hay citas en el rango indicado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
