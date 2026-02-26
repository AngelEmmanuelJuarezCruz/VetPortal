<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">Movimientos de Stock</h1>
                    <a href="{{ route('reportes.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Volver</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($movimientos as $mov)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mov->producto->nombre }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($mov->tipo === 'entrada')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Entrada</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Salida</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $mov->cantidad }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mov->motivo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mov->referencia ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mov->user->name ?? 'Sistema' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mov->fecha->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay movimientos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
