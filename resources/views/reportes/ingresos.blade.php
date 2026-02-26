<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Reporte de Ingresos Mensuales</h1>
                    <a href="{{ route('reportes.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Volver</a>
                </div>
            </div>

            <!-- Filtro de año -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <form method="GET" class="flex gap-4">
                    <select name="ano" class="px-4 py-2 border border-gray-300 rounded-lg">
                        @for ($a = now()->year - 2; $a <= now()->year; $a++)
                            <option value="{{ $a }}" @selected($ano == $a)>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">Filtrar</button>
                </form>
            </div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Ingresos Totales {{ $ano }}</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($ingresos_totales, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Promedio Mensual</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($promedio_mensual, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Mes con Mayor Ingreso</p>
                    <p class="text-2xl font-bold text-gray-800">{{ max($ingresos_mensuales) > 0 ? array_key_first(array_filter($ingresos_mensuales, fn($v) => $v == max($ingresos_mensuales))) : 'N/A' }}</p>
                </div>
            </div>

            <!-- Tabla de ingresos mensuales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingresos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">% del Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visualizacion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($ingresos_mensuales as $mes => $monto)
                            @php
                                $porcentaje = $ingresos_totales > 0 ? ($monto / $ingresos_totales) * 100 : 0;
                                $ancho = intval($porcentaje);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mes }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($monto, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ round($porcentaje, 1) }}%</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $ancho }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">TOTAL</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($ingresos_totales, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">100%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
