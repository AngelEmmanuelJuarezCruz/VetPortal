<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Historial Clínico de: {{ $mascota->nombre }}</h1>
                        <a href="{{ route('mascotas.clinical-histories.create', $mascota) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Añadir Entrada
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        @if($histories->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">No hay historial clínico para esta mascota.</p>
                            </div>
                        @else
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">
                                        <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Fecha Visita
                                        </th>
                                        <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Motivo
                                        </th>
                                        <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Veterinario
                                        </th>
                                        <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($histories as $history)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                                {{ \Carbon\Carbon::parse($history->visit_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                                {{ $history->reason }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                                {{ $history->veterinarian->nombre ?? 'No especificado' }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                                <button onclick="showDetails({{ $history }})" class="text-blue-500 hover:text-blue-700">Ver Detalles</button>
                                                <form action="{{ route('clinical-histories.destroy', $history) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta entrada?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-4">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-4">
                                {{ $histories->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="detailsModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Detalles del Historial Clínico
                    </h3>
                    <div class="mt-4">
                        <p><strong>Fecha:</strong> <span id="modal-date"></span></p>
                        <p><strong>Motivo:</strong> <span id="modal-reason"></span></p>
                        <p><strong>Diagnóstico:</strong> <span id="modal-diagnosis"></span></p>
                        <p><strong>Tratamiento:</strong> <span id="modal-treatment"></span></p>
                        <p><strong>Notas:</strong> <span id="modal-notes"></span></p>
                        <p><strong>Veterinario:</strong> <span id="modal-veterinarian"></span></p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(history) {
            document.getElementById('modal-date').textContent = new Date(history.visit_date).toLocaleDateString('es-ES');
            document.getElementById('modal-reason').textContent = history.reason;
            document.getElementById('modal-diagnosis').textContent = history.diagnosis;
            document.getElementById('modal-treatment').textContent = history.treatment;
            document.getElementById('modal-notes').textContent = history.notes || 'N/A';
            document.getElementById('modal-veterinarian').textContent = history.veterinarian ? history.veterinarian.nombre : 'No especificado';
            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
