<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Editar Servicio</h1>
                    <p class="text-gray-600 mb-6">Actualiza la informacion del servicio.</p>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">¡Error!</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('servicios.update', $servicio) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $servicio->nombre) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria *</label>
                                <select name="categoria" id="categoria" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                    <option value="salud" @selected(old('categoria', $servicio->categoria) == 'salud')>Salud</option>
                                    <option value="estetica" @selected(old('categoria', $servicio->categoria) == 'estetica')>Estetica</option>
                                    <option value="cirugia" @selected(old('categoria', $servicio->categoria) == 'cirugia')>Cirugia</option>
                                    <option value="bienestar" @selected(old('categoria', $servicio->categoria) == 'bienestar')>Bienestar</option>
                                </select>
                            </div>
                            <div>
                                <label for="duracion_estimada" class="block text-sm font-medium text-gray-700">Duracion Estimada (min)</label>
                                <input type="number" min="1" name="duracion_estimada" id="duracion_estimada" value="{{ old('duracion_estimada', $servicio->duracion_estimada) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripcion</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" min="0" step="0.01" name="precio" id="precio" value="{{ old('precio', $servicio->precio) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="activo" class="block text-sm font-medium text-gray-700">Activo *</label>
                                <select name="activo" id="activo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                    <option value="1" @selected(old('activo', $servicio->activo ? '1' : '0') == '1')>Si</option>
                                    <option value="0" @selected(old('activo', $servicio->activo ? '1' : '0') == '0')>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('servicios.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
