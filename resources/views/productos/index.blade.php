<x-app-layout>
    <div class="py-12" x-data="{ categoriaActiva: 'todas' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-3xl font-bold text-gray-900">Inventario</h1>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('movimientos-stock.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg">Movimientos</a>
                        <a href="{{ route('productos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Nuevo Producto</a>
                    </div>
                </div>
            </div>

            <!-- Categorías -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Categorías</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <!-- Todas -->
                    <button @click="categoriaActiva = 'todas'" :class="categoriaActiva === 'todas' ? 'bg-purple-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-purple-500 transition-all duration-200">
                        <i class="fas fa-th text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Todas</span>
                    </button>

                    <!-- Vacunas -->
                    <button @click="categoriaActiva = 'Vacunas'" :class="categoriaActiva === 'Vacunas' ? 'bg-blue-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-blue-500 transition-all duration-200">
                        <i class="fas fa-syringe text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Vacunas</span>
                    </button>

                    <!-- Antiparasitarios -->
                    <button @click="categoriaActiva = 'Antiparasitarios'" :class="categoriaActiva === 'Antiparasitarios' ? 'bg-green-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-green-500 transition-all duration-200">
                        <i class="fas fa-bug text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Antiparasitarios</span>
                    </button>

                    <!-- Antibióticos -->
                    <button @click="categoriaActiva = 'Antibióticos'" :class="categoriaActiva === 'Antibióticos' ? 'bg-red-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-red-500 transition-all duration-200">
                        <i class="fas fa-pills text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Antibióticos</span>
                    </button>

                    <!-- Material Médico -->
                    <button @click="categoriaActiva = 'Material Médico'" :class="categoriaActiva === 'Material Médico' ? 'bg-yellow-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-yellow-500 transition-all duration-200">
                        <i class="fas fa-briefcase-medical text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Material Médico</span>
                    </button>

                    <!-- Accesorios -->
                    <button @click="categoriaActiva = 'Accesorios'" :class="categoriaActiva === 'Accesorios' ? 'bg-pink-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-pink-500 transition-all duration-200">
                        <i class="fas fa-paw text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Accesorios</span>
                    </button>

                    <!-- Higiene -->
                    <button @click="categoriaActiva = 'Higiene'" :class="categoriaActiva === 'Higiene' ? 'bg-indigo-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-500 transition-all duration-200">
                        <i class="fas fa-soap text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Higiene</span>
                    </button>

                    <!-- Alimentos Veterinarios -->
                    <button @click="categoriaActiva = 'Alimentos Veterinarios'" :class="categoriaActiva === 'Alimentos Veterinarios' ? 'bg-orange-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-orange-500 transition-all duration-200">
                        <i class="fas fa-drumstick-bite text-4xl mb-3"></i>
                        <span class="font-semibold text-sm">Alimentos</span>
                    </button>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('productos.index') }}" class="flex gap-4">
                        <input type="text" name="search" placeholder="Buscar por nombre, tipo, categoria o proveedor" value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo / Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caducidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alertas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($productos as $producto)
                            @php
                                $caducidad = $producto->fecha_caducidad;
                                $expiraPronto = $caducidad ? $caducidad->isBetween(now(), now()->addDays(30)) : false;
                                $bajoStock = $producto->stock_actual <= $producto->stock_minimo;
                            @endphp
                            <tr class="hover:bg-gray-50" 
                                x-show="categoriaActiva === 'todas' || categoriaActiva === '{{ $producto->categoria }}'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ $producto->proveedor ?? 'Sin proveedor' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ ucfirst($producto->tipo) }}
                                    <div class="text-xs text-gray-500">{{ $producto->categoria ?? 'Sin categoria' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $producto->stock_actual }}
                                    <div class="text-xs text-gray-500">Min: {{ $producto->stock_minimo }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    ${{ number_format($producto->precio_venta, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $caducidad ? $caducidad->format('d/m/Y') : 'No aplica' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($bajoStock)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Stock bajo</span>
                                        @endif
                                        @if ($expiraPronto)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Por caducar</span>
                                        @endif
                                        @if (!$bajoStock && !$expiraPronto)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">OK</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 hover:text-blue-900 mr-4">Editar</a>
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No se encontraron productos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
