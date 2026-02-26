<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Reporte de Consumo de Inventario</h1>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total Consumido</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $total_consumido }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Valor Consumido</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($valor_total, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Productos Consumidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $consumo->count() }}</p>
                </div>
            </div>

            <!-- Tabla de consumo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Movimientos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Consumido</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($consumo as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['producto']->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item['cantidad_total'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item['movimientos'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($item['producto']->precio_venta, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">${{ number_format($item['valor_consumido'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">TOTAL</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $total_consumido }}</td>
                            <td colspan="2"></td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${{ number_format($valor_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
