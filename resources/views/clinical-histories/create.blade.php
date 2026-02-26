<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6">Añadir Entrada al Historial de: {{ $mascota->nombre }}</h1>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Ups!</strong>
                            <span class="block sm:inline">Hay algunos problemas con tu entrada.</span>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('mascotas.clinical-histories.store', $mascota) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Visit Date -->
                            <div>
                                <label for="visit_date" class="block text-sm font-medium text-gray-700">Fecha de Visita</label>
                                <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <!-- Veterinarian -->
                            <div>
                                <label for="veterinarian_id" class="block text-sm font-medium text-gray-700">Veterinario</label>
                                <select name="veterinarian_id" id="veterinarian_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Seleccionar Veterinario</option>
                                    @foreach($veterinarians as $veterinarian)
                                        <option value="{{ $veterinarian->id }}" {{ old('veterinarian_id') == $veterinarian->id ? 'selected' : '' }}>
                                            {{ $veterinarian->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mt-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Motivo de la Visita</label>
                            <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <!-- Diagnosis -->
                        <div class="mt-4">
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                            <textarea name="diagnosis" id="diagnosis" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('diagnosis') }}</textarea>
                        </div>

                        <!-- Treatment -->
                        <div class="mt-4">
                            <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento</label>
                            <textarea name="treatment" id="treatment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('treatment') }}</textarea>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('mascotas.clinical-histories.index', $mascota) }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
