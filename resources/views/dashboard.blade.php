<x-app-layout>
    <div class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Clientes</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['clients'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <i class="fas fa-paw fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Mascotas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['pets'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Citas para Hoy</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['appointments_today'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
                        <i class="fas fa-user-md fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Veterinarios Activos</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['active_veterinarians'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-orange-100 text-orange-600 p-3 rounded-full">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ingresos Este Mes</p>
                        <p class="text-3xl font-bold text-gray-800">${{ number_format($stats['monthly_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Valor Inventario</p>
                        <p class="text-3xl font-bold text-gray-800">${{ number_format($stats['inventory_value'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        @if($stats['low_stock_count'] > 0 || $stats['expiring_count'] > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($stats['low_stock_count'] > 0)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-yellow-800">Stock Bajo</h4>
                                <p class="text-sm text-yellow-700 mt-1">{{ $stats['low_stock_count'] }} producto(s) con stock por debajo del minimo</p>
                                <a href="{{ route('productos.index') }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm mt-2 inline-block">Ver detalles →</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($stats['expiring_count'] > 0)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-times text-red-600 text-2xl"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-red-800">Por Caducar</h4>
                                <p class="text-sm text-red-700 mt-1">{{ $stats['expiring_count'] }} producto(s) vencen en los proximos 30 dias</p>
                                <a href="{{ route('productos.index') }}" class="text-red-600 hover:text-red-800 font-medium text-sm mt-2 inline-block">Ver detalles →</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('clients.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-user-plus fa-2x text-blue-600"></i>
                    <span class="mt-2 text-sm font-medium text-gray-700">Nuevo Cliente</span>
                </a>
                <a href="{{ route('pets.create') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-paw fa-2x text-green-600"></i>
                    <span class="mt-2 text-sm font-medium text-gray-700">Nueva Mascota</span>
                </a>
                <a href="{{ route('appointments.create') }}" class="flex flex-col items-center justify-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <i class="fas fa-calendar-plus fa-2x text-yellow-600"></i>
                    <span class="mt-2 text-sm font-medium text-gray-700">Agendar Cita</span>
                </a>
                <a href="{{ route('veterinarians.create') }}" class="flex flex-col items-center justify-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                    <i class="fas fa-user-md fa-2x text-indigo-600"></i>
                    <span class="mt-2 text-sm font-medium text-gray-700">Nuevo Veterinario</span>
                </a>
                <a href="{{ route('reportes.index') }}" class="flex flex-col items-center justify-center p-4 bg-pink-50 hover:bg-pink-100 rounded-lg transition-colors">
                    <i class="fas fa-chart-bar fa-2x text-pink-600"></i>
                    <span class="mt-2 text-sm font-medium text-gray-700">Reportes</span>
                </a>
            </div>
        </div>

        <!-- Top Services & Products Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Services -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Servicios Más Vendidos (30 días)</h3>
                    <a href="{{ route('reportes.servicios') }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver más</a>
                </div>
                @if($top_services->count() > 0)
                    <div class="space-y-3">
                        @foreach($top_services as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $item['servicio']->nombre }}</p>
                                    <p class="text-xs text-gray-500">Vendidas: {{ $item['cantidad'] }} veces</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-green-600">${{ number_format($item['ingreso'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Sin servicios en el periodo.</p>
                @endif
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Productos con Bajo Stock</h3>
                    <a href="{{ route('productos.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver inventario</a>
                </div>
                @if($low_stock_products->count() > 0)
                    <div class="space-y-3">
                        @foreach($low_stock_products as $product)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $product->nombre }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $product->stock_actual }} / Min: {{ $product->stock_minimo }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">Bajo</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Todos los productos con stock suficiente.</p>
                @endif
            </div>
        </div>

        <!-- Recent Pets Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-800 mb-6">Últimas Mascotas Registradas</h3>
            @if($recent_pets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($recent_pets as $pet)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                            <!-- Pet Image -->
                            <div class="h-40 bg-gradient-to-br from-blue-200 to-purple-200 flex items-center justify-center relative overflow-hidden">
                                @if($pet->foto)
                                    <img src="{{ asset('storage/' . $pet->foto) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-paw text-4xl text-white opacity-30"></i>
                                @endif
                                <button class="absolute top-2 right-2 bg-white rounded-full p-2 hover:bg-red-50 transition-colors shadow-md" title="Favorito">
                                    <i class="fas fa-heart text-gray-300 hover:text-red-500"></i>
                                </button>
                            </div>
                            
                            <!-- Pet Info -->
                            <div class="p-4">
                                <h4 class="text-lg font-bold text-gray-800">{{ $pet->nombre }}</h4>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    @if($pet->especie)
                                        <p><i class="fas fa-tag w-4"></i> <span class="text-gray-700 font-medium">{{ $pet->especie }}</span></p>
                                    @endif
                                    @if($pet->raza)
                                        <p><i class="fas fa-dna w-4"></i> <span class="text-gray-700">{{ $pet->raza }}</span></p>
                                    @endif
                                    @if($pet->color)
                                        <p><i class="fas fa-palette w-4"></i> <span class="text-gray-700">{{ $pet->color }}</span></p>
                                    @endif
                                    @if($pet->fecha_nacimiento)
                                        <p><i class="fas fa-birthday-cake w-4"></i> <span class="text-gray-700">{{ $pet->edad_formatted }}</span></p>
                                    @endif
                                    @if($pet->cliente)
                                        <p><i class="fas fa-user w-4"></i> <span class="text-gray-700">{{ $pet->cliente->nombre }}</span></p>
                                    @endif
                                </div>
                                <a href="{{ route('pets.edit', $pet) }}" class="mt-4 block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors text-sm">
                                    <i class="fas fa-edit mr-2"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-paw fa-3x"></i>
                    <p class="mt-2">No hay mascotas registradas aún.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>