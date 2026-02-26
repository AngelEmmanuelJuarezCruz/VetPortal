<x-app-layout>
    <div class="py-12" x-data="{ categoriaActiva: 'todas' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-3xl font-bold text-gray-900">Servicios</h1>
                    <a href="{{ route('servicios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Nuevo Servicio</a>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Categorias</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <button @click="categoriaActiva = 'todas'" :class="categoriaActiva === 'todas' ? 'bg-purple-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-purple-500 transition-all duration-200">
                        <i class="fas fa-th text-3xl mb-2"></i>
                        <span class="font-semibold text-sm">Todas</span>
                    </button>
                    <button @click="categoriaActiva = 'salud'" :class="categoriaActiva === 'salud' ? 'bg-emerald-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-emerald-500 transition-all duration-200">
                        <i class="fas fa-heartbeat text-3xl mb-2"></i>
                        <span class="font-semibold text-sm">Salud</span>
                    </button>
                    <button @click="categoriaActiva = 'estetica'" :class="categoriaActiva === 'estetica' ? 'bg-pink-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-pink-500 transition-all duration-200">
                        <i class="fas fa-spa text-3xl mb-2"></i>
                        <span class="font-semibold text-sm">Estetica</span>
                    </button>
                    <button @click="categoriaActiva = 'cirugia'" :class="categoriaActiva === 'cirugia' ? 'bg-red-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-red-500 transition-all duration-200">
                        <i class="fas fa-procedures text-3xl mb-2"></i>
                        <span class="font-semibold text-sm">Cirugia</span>
                    </button>
                    <button @click="categoriaActiva = 'bienestar'" :class="categoriaActiva === 'bienestar' ? 'bg-indigo-600 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:shadow-lg hover:scale-105'" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-500 transition-all duration-200">
                        <i class="fas fa-leaf text-3xl mb-2"></i>
                        <span class="font-semibold text-sm">Bienestar</span>
                    </button>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('servicios.index') }}" class="flex gap-4">
                        <input type="text" name="search" placeholder="Buscar por nombre o categoria" value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duracion</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($servicios as $servicio)
                            <tr class="hover:bg-gray-50"
                                x-show="categoriaActiva === 'todas' || categoriaActiva === '{{ $servicio->categoria }}'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $servicio->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ $servicio->descripcion ?? 'Sin descripcion' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($servicio->categoria) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $servicio->duracion_estimada ? $servicio->duracion_estimada . ' min' : 'Sin definir' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${{ number_format($servicio->precio, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($servicio->activo)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('servicios.edit', $servicio) }}" class="text-blue-600 hover:text-blue-900 mr-4">Editar</a>
                                    <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron servicios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $servicios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
