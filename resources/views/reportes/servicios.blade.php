<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Reporte de Servicios</h1>
                    <a href="{{ route('reportes.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Volver</a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <form method="GET" class="flex gap-4">
                    <div class="flex gap-4">
                        <select name="mes" class="px-4 py-2 border border-gray-300 rounded-lg">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @selected($mes == $m)>{{ \Carbon\Carbon::createFromDate(2026, $m, 1)->format('F') }}</option>
                            @endfor
                        </select>
                        <select name="ano" class="px-4 py-2 border border-gray-300 rounded-lg">
                            @for ($a = now()->year - 2; $a <= now()->year; $a++)
                                <option value="{{ $a }}" @selected($ano == $a)>{{ $a }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">Filtrar</button>
                    </div>
                </form>
            </div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total Servicios Vendidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $servicios_vendidos->sum('cantidad') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($ingresos_totales, 2) }}</p>
                </div>
            </div>

            <!-- Tabla de servicios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad Vendida</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($servicios_vendidos as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['servicio']->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($item['servicio']->categoria) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item['cantidad'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($item['servicio']->precio, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($item['ingresos'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">TOTAL</td>
                            <td colspan="2"></td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $servicios_vendidos->sum('cantidad') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($ingresos_totales, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
