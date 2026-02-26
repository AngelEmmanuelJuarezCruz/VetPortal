<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Clientes</h1>
                    <a href="{{ route('clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Agregar Cliente
                    </a>
                </div>
            </div>

            <!-- Search Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('clients.index') }}" class="flex gap-4">
                        <input type="text" name="search" placeholder="Buscar por nombre, apellido, teléfono, correo o UUID..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UUID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mascotas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $client->nombre }} {{ $client->apellido }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $client->correo }}</div>
                                    <div class="text-sm text-gray-600"><i class="fas fa-phone mr-2 text-gray-400"></i>{{ $client->telefono }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-mono text-xs">{{ substr($client->uuid, 0, 8) }}...</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-paw mr-1"></i>{{ $client->mascotas_count ?? $client->mascotas->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este cliente? Todas sus mascotas también serán eliminadas.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash mr-1"></i>Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-gray-400 text-5xl mb-4"></i>
                                        <p class="text-lg font-medium">No se encontraron clientes</p>
                                        <p class="text-sm mt-2">Comienza agregando tu primer cliente</p>
                                        <a href="{{ route('clients.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                            <i class="fas fa-plus mr-2"></i>Agregar Cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($clients->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>