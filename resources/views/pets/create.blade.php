<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800">Registrar Mascota</h1>

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

                    <form method="POST" action="{{ route('pets.store') }}" class="">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Cliente -->
                            <div>
                                <label for="cliente_id" class="block text-sm font-medium text-gray-700">Dueño (Cliente)</label>
                                <select id="cliente_id" name="cliente_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecciona un cliente...</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ (string) old('cliente_id', $selectedClientId) === (string) $client->id ? 'selected' : '' }}>
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
                                <input type="text" id="especie" name="especie" value="{{ old('especie') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Raza -->
                            <div>
                                <label for="raza" class="block text-sm font-medium text-gray-700">Raza</label>
                                <input type="text" id="raza" name="raza" value="{{ old('raza') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                <input type="text" id="color" name="color" value="{{ old('color') }}" placeholder="Ej: Negro, Café, Blanco..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                        </div>

                        <!-- Foto -->
                        <div class="mt-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto de la Mascota</label>
                            <input type="file" id="foto" name="foto" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Formatos soportados: JPG, PNG, GIF. Máximo 2MB</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('pets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Registrar Mascota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>