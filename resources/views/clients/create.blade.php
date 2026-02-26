<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Crear Nuevo Cliente</h1>
                    <p class="text-gray-600 mb-6">Completa la información del cliente y sus mascotas (opcional).</p>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clients.store') }}" method="POST" id="clientForm">
                        @csrf
                        
                        <!-- SECIÓN: INFORMACIÓN DEL CLIENTE -->
                        <div class="mb-8 pb-8 border-b">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Información del Cliente</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('nombre')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                </div>

                                <!-- Apellido -->
                                <div>
                                    <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido *</label>
                                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('apellido')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <!-- Email -->
                                <div>
                                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                                    <input type="email" name="correo" id="correo" value="{{ old('correo') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('correo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                </div>

                                <!-- Telefono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('telefono')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN: MASCOTAS -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">Mascotas del Cliente (Opcional)</h2>
                                <button type="button" onclick="addPet()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    <i class="fas fa-plus mr-2"></i>Añadir Mascota
                                </button>
                            </div>

                            <div id="petsContainer" class="space-y-4">
                                <!-- Las mascotas se agregarán dinámicamente aquí -->
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                <i class="fas fa-save mr-2"></i>Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let petCount = 0;
        
        function addPet() {
            const container = document.getElementById('petsContainer');
            const petIndex = petCount++;
            
            const petHTML = `
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 pet-item" id="pet-${petIndex}">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Mascota #${petIndex + 1}</h3>
                        <button type="button" onclick="removePet(${petIndex})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Eliminar
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" name="pets[${petIndex}][nombre]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Especie -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Especie *</label>
                            <select name="pets[${petIndex}][especie]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecciona una especie</option>
                                <option value="Perro">Perro</option>
                                <option value="Gato">Gato</option>
                                <option value="Conejo">Conejo</option>
                                <option value="Hamster">Hamster</option>
                                <option value="Ave">Ave</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <!-- Raza -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Raza</label>
                            <input type="text" name="pets[${petIndex}][raza]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Color</label>
                            <input type="text" name="pets[${petIndex}][color]" placeholder="Ej: Negro, Café, Blanco..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" name="pets[${petIndex}][fecha_nacimiento]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="pets[${petIndex}][descripcion]" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Información adicional sobre la mascota..."></textarea>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', petHTML);
        }
        
        function removePet(index) {
            const petElement = document.getElementById(`pet-${index}`);
            if (petElement) {
                petElement.remove();
            }
        }
    </script>
</x-app-layout>