<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Agendar Nueva Cita</h1>
                    <p class="text-gray-600 mb-6">Completa los datos para agendar una cita veterinaria.</p>

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

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <!-- Cliente - Búsqueda por Teléfono -->
                        <div class="mb-6">
                            <label for="telefono_search" class="block text-sm font-medium text-gray-700">Buscar Cliente por Teléfono *</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="telefono_search" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Escribe el número de teléfono..."
                                    autocomplete="off"
                                >
                                
                                <!-- Hidden input for selected client ID -->
                                <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}" required>
                                
                                <!-- Search results dropdown -->
                                <div id="search_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                            </div>
                            
                            <!-- Selected client display -->
                            <div id="selected_client" class="mt-2 hidden">
                                <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-md">
                                    <div>
                                        <p class="font-semibold text-gray-900" id="client_name"></p>
                                        <p class="text-sm text-gray-600" id="client_phone"></p>
                                    </div>
                                    <button type="button" onclick="clearClientSelection()" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- New client link -->
                            <div id="no_results_message" class="mt-2 hidden">
                                <p class="text-sm text-gray-600">No se encontraron clientes. 
                                    <a href="{{ route('clients.create') }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold">Registrar nuevo cliente</a>
                                </p>
                            </div>
                            
                            @error('cliente_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        @php
                            $selectedMascotas = old('mascotas', []);
                            if (empty($selectedMascotas) && old('mascota_id')) {
                                $selectedMascotas = [old('mascota_id')];
                            }
                        @endphp

                        <!-- Mascotas -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Mascotas (opcional)</label>
                            <div id="mascotas_container" class="space-y-2 p-4 border border-gray-300 rounded-md bg-gray-50 min-h-24">
                                <p class="text-sm text-gray-500">Selecciona un cliente primero para ver sus mascotas</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Puedes seleccionar una o varias mascotas</p>
                            @error('mascotas')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Veterinario -->
                        <div class="mb-6">
                            <label for="veterinario_id" class="block text-sm font-medium text-gray-700">Veterinario *</label>
                            <select name="veterinario_id" id="veterinario_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un veterinario</option>
                                @foreach ($veterinarians as $vet)
                                    <option value="{{ $vet->id }}" @selected(old('veterinario_id') == $vet->id)>
                                        {{ $vet->nombre }} ({{ $vet->especialidad }})
                                    </option>
                                @endforeach
                            </select>
                            @error('veterinario_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Grid: Fecha y Hora -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('fecha')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="hora" class="block text-sm font-medium text-gray-700">Hora *</label>
                                <input type="time" name="hora" id="hora" value="{{ old('hora') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('hora')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div class="mb-6">
                            <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo de la Consulta *</label>
                            <textarea name="motivo" id="motivo" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('motivo') }}</textarea>
                            @error('motivo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-6">
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                            <select name="estado" id="estado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pendiente" @selected(old('estado') == 'pendiente')>Pendiente</option>
                                <option value="confirmada" @selected(old('estado') == 'confirmada')>Confirmada</option>
                                <option value="cancelada" @selected(old('estado') == 'cancelada')>Cancelada</option>
                                <option value="finalizada" @selected(old('estado') == 'finalizada')>Finalizada</option>
                            </select>
                            @error('estado')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        @php
                            $selectedServicios = old('servicios', []);
                            $selectedProductos = old('productos', []);
                            $serviciosCantidad = old('servicios_cantidad', []);
                            $productosCantidad = old('productos_cantidad', []);
                        @endphp

                        <!-- Servicios -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Servicios</h2>
                            <div class="space-y-3">
                                @forelse ($services as $service)
                                    <div class="flex flex-wrap items-center gap-4 border rounded-lg p-3">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="servicios[]" value="{{ $service->id }}" @checked(in_array($service->id, $selectedServicios))>
                                            <span class="text-sm font-medium text-gray-800">{{ $service->nombre }}</span>
                                        </label>
                                        <span class="text-xs text-gray-500">{{ ucfirst($service->categoria) }} · ${{ number_format($service->precio, 2) }}</span>
                                        <div class="ml-auto flex items-center gap-2">
                                            <label class="text-xs text-gray-600">Cantidad</label>
                                            <input type="number" name="servicios_cantidad[{{ $service->id }}]" min="1" value="{{ $serviciosCantidad[$service->id] ?? 1 }}" class="w-20 px-2 py-1 border border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No hay servicios activos registrados.</p>
                                @endforelse
                            </div>
                            @error('servicios')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Productos -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Productos / Inventario</h2>
                            <div class="space-y-3">
                                @forelse ($products as $product)
                                    <div class="flex flex-wrap items-center gap-4 border rounded-lg p-3">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="productos[]" value="{{ $product->id }}" @checked(in_array($product->id, $selectedProductos))>
                                            <span class="text-sm font-medium text-gray-800">{{ $product->nombre }}</span>
                                        </label>
                                        <span class="text-xs text-gray-500">{{ ucfirst($product->tipo) }} · Stock: {{ $product->stock_actual }}</span>
                                        <div class="ml-auto flex items-center gap-2">
                                            <label class="text-xs text-gray-600">Cantidad</label>
                                            <input type="number" name="productos_cantidad[{{ $product->id }}]" min="1" value="{{ $productosCantidad[$product->id] ?? 1 }}" class="w-20 px-2 py-1 border border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No hay productos registrados.</p>
                                @endforelse
                            </div>
                            @error('productos')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                <i class="fas fa-save mr-2"></i>Agendar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $preselectedClient = null;
        if (old('cliente_id')) {
            $preselectedClient = \App\Models\Cliente::find(old('cliente_id'));
        }
    @endphp

    <script>
        let searchTimeout = null;
        const selectedMascotas = @json($selectedMascotas).map(id => parseInt(id, 10));
        const preselectedClient = @json($preselectedClient);
        
        // Search clients by phone number
        function searchClients(telefono) {
            const searchResults = document.getElementById('search_results');
            const noResultsMessage = document.getElementById('no_results_message');
            
            if (telefono.length < 3) {
                searchResults.classList.add('hidden');
                noResultsMessage.classList.add('hidden');
                return;
            }
            
            fetch(`/api/clients/search-by-phone?telefono=${encodeURIComponent(telefono)}`)
                .then(response => response.json())
                .then(clients => {
                    if (clients.length === 0) {
                        searchResults.classList.add('hidden');
                        noResultsMessage.classList.remove('hidden');
                        return;
                    }
                    
                    noResultsMessage.classList.add('hidden');
                    searchResults.innerHTML = '';
                    clients.forEach(client => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                        div.dataset.clientId = client.id;
                        div.dataset.clientNombre = client.nombre;
                        div.dataset.clientApellido = client.apellido;
                        div.dataset.clientTelefono = client.telefono;
                        div.innerHTML = `
                            <p class="font-semibold text-gray-900">${client.nombre} ${client.apellido}</p>
                            <p class="text-sm text-gray-600">${client.telefono}</p>
                        `;
                        div.addEventListener('click', function() {
                            selectClient(this.dataset.clientId, this.dataset.clientNombre, this.dataset.clientApellido, this.dataset.clientTelefono);
                        });
                        searchResults.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchResults.innerHTML = '<div class="px-4 py-3 text-red-600">Error al buscar clientes</div>';
                    searchResults.classList.remove('hidden');
                });
        }
        
        // Select a client from search results
        function selectClient(id, nombre, apellido, telefono) {
            document.getElementById('cliente_id').value = id;
            document.getElementById('telefono_search').value = '';
            document.getElementById('search_results').classList.add('hidden');
            document.getElementById('no_results_message').classList.add('hidden');
            
            const selectedClientDiv = document.getElementById('selected_client');
            document.getElementById('client_name').textContent = `${nombre} ${apellido}`;
            document.getElementById('client_phone').textContent = telefono;
            selectedClientDiv.classList.remove('hidden');
            
            // Load pets for selected client
            loadPets();
        }
        
        // Clear client selection
        function clearClientSelection() {
            document.getElementById('cliente_id').value = '';
            document.getElementById('telefono_search').value = '';
            document.getElementById('selected_client').classList.add('hidden');
            
            // Clear pets
            const mascotasContainer = document.getElementById('mascotas_container');
            mascotasContainer.innerHTML = '<p class="text-sm text-gray-500">Selecciona un cliente primero para ver sus mascotas</p>';
        }
        
        // Load pets for selected client
        function loadPets() {
            const clienteId = document.getElementById('cliente_id').value;
            const mascotasContainer = document.getElementById('mascotas_container');
            
            if (!clienteId) {
                mascotasContainer.innerHTML = '<p class="text-sm text-gray-500">Selecciona un cliente primero para ver sus mascotas</p>';
                return;
            }

            fetch(`/api/clients/${clienteId}/pets`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        mascotasContainer.innerHTML = '<p class="text-sm text-gray-500">Este cliente no tiene mascotas registradas</p>';
                        return;
                    }

                    let html = '';
                    data.forEach(pet => {
                        const isChecked = selectedMascotas.includes(pet.id) ? 'checked' : '';
                        html += `
                            <label class="flex items-center space-x-2 p-2 hover:bg-white rounded-md cursor-pointer transition">
                                <input type="checkbox" name="mascotas[]" value="${pet.id}" ${isChecked} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">${pet.nombre} <span class="text-gray-500">(${pet.especie})</span></span>
                            </label>
                        `;
                    });
                    mascotasContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    mascotasContainer.innerHTML = '<p class="text-sm text-red-600">Error al cargar mascotas</p>';
                });
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const telefonoSearch = document.getElementById('telefono_search');
            
            // Search as user types (with debounce)
            telefonoSearch.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchClients(e.target.value);
                }, 300);
            });
            
            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#telefono_search') && !e.target.closest('#search_results')) {
                    document.getElementById('search_results').classList.add('hidden');
                }
            });
            
            // Show preselected client if exists (for validation errors)
            if (preselectedClient) {
                document.getElementById('client_name').textContent = `${preselectedClient.nombre} ${preselectedClient.apellido}`;
                document.getElementById('client_phone').textContent = preselectedClient.telefono;
                document.getElementById('selected_client').classList.remove('hidden');
            }
            
            // Load pets if client was already selected (for validation errors)
            loadPets();
        });
    </script>
</x-app-layout>
