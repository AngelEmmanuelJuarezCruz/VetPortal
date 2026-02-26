<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Mascota</h1>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Ups!</strong>
                            <span class="block sm:inline">Hay algunos problemas con tu entrada.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Pet Info Card -->
                    @if($pet->foto || $pet->edad_formatted)
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 mb-6">
                            <div class="flex gap-4">
                                @if($pet->foto)
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-200 to-purple-200 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="{{ asset('storage/' . $pet->foto) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">{{ $pet->nombre }}</h2>
                                    @if($pet->edad_formatted)
                                        <p class="text-sm text-gray-600"><i class="fas fa-birthday-cake mr-2"></i>{{ $pet->edad_formatted }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pets.update', $pet) }}" enctype="multipart/form-data" class="">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $pet->nombre) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Cliente -->
                            <div>
                                <label for="cliente_id" class="block text-sm font-medium text-gray-700">Dueño (Cliente)</label>
                                <select id="cliente_id" name="cliente_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecciona un cliente...</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('cliente_id', $pet->cliente_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->nombre }} {{ $client->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Especie -->
                            <div>
                                <label for="especie" class="block text-sm font-medium text-gray-700">Especie</label>
                                <input type="text" id="especie" name="especie" value="{{ old('especie', $pet->especie) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Raza -->
                            <div>
                                <label for="raza" class="block text-sm font-medium text-gray-700">Raza</label>
                                <input type="text" id="raza" name="raza" value="{{ old('raza', $pet->raza) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                <input type="text" id="color" name="color" value="{{ old('color', $pet->color) }}" placeholder="Ej: Negro, Café, Blanco..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $pet->fecha_nacimiento) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('descripcion', $pet->descripcion) }}</textarea>
                        </div>

                        <!-- Foto -->
                        <div class="mt-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto de la Mascota</label>
                            @if($pet->foto)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Foto actual:</p>
                                    <img src="{{ asset('storage/' . $pet->foto) }}" alt="{{ $pet->nombre }}" class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            @endif
                            <input type="file" id="foto" name="foto" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Formatos soportados: JPG, PNG, GIF. Máximo 2MB. Dejá en blanco para mantener la foto actual.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('pets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Actualizar Mascota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>