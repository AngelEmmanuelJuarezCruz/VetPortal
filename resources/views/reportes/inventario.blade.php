<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Reporte de Inventario</h1>
                    <a href="{{ route('reportes.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Volver</a>
                </div>
            </div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Total Productos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $resumen['total_productos'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Valor Total</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($resumen['total_valor'], 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Bajo Stock</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $resumen['bajo_stock'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <p class="text-sm font-medium text-gray-500">Por Caducar</p>
                    <p class="text-2xl font-bold text-red-600">{{ $resumen['por_caducar'] }}</p>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caducidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($productos as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($p->tipo) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $p->categoria ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $p->stock_actual }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $p->stock_minimo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($p->precio_venta, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">${{ number_format($p->stock_actual * $p->precio_venta, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($p->fecha_caducidad)
                                        {{ $p->fecha_caducidad->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
