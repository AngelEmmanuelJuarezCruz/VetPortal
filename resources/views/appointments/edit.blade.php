<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Editar Cita</h1>
                    <p class="text-gray-600 mb-6">Actualiza los datos de la cita veterinaria.</p>

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

                    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Cliente -->
                        <div class="mb-6">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required onchange="loadPets()">
                                <option value="">Selecciona un cliente</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected($appointment->cliente_id == $client->id)>
                                        {{ $client->nombre }} {{ $client->apellido }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        @php
                            $selectedMascotas = old('mascotas', $appointment->mascotas->pluck('id')->toArray());
                            if (empty($selectedMascotas) && $appointment->mascota_id) {
                                $selectedMascotas = [$appointment->mascota_id];
                            }
                            if (empty($selectedMascotas) && old('mascota_id')) {
                                $selectedMascotas = [old('mascota_id')];
                            }
                        @endphp

                        <!-- Mascotas -->
                        <div class="mb-6">
                            <label for="mascotas" class="block text-sm font-medium text-gray-700">Mascotas (opcional)</label>
                            <select name="mascotas[]" id="mascotas" multiple size="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled>Selecciona una o varias mascotas</option>
                                @foreach ($mascots as $mascota)
                                    <option value="{{ $mascota->id }}" @selected(in_array($mascota->id, $selectedMascotas))>
                                        {{ $mascota->nombre }} ({{ $mascota->especie }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Usa Ctrl o Cmd para seleccionar varias.</p>
                            @error('mascotas')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Veterinario -->
                        <div class="mb-6">
                            <label for="veterinario_id" class="block text-sm font-medium text-gray-700">Veterinario *</label>
                            <select name="veterinario_id" id="veterinario_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecciona un veterinario</option>
                                @foreach ($veterinarians as $vet)
                                    <option value="{{ $vet->id }}" @selected($appointment->veterinario_id == $vet->id)>
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
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $appointment->fecha) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('fecha')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="hora" class="block text-sm font-medium text-gray-700">Hora *</label>
                                <input type="time" name="hora" id="hora" value="{{ old('hora', $appointment->hora) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('hora')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div class="mb-6">
                            <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo de la Consulta *</label>
                            <textarea name="motivo" id="motivo" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('motivo', $appointment->motivo) }}</textarea>
                            @error('motivo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-6">
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                            <select name="estado" id="estado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pendiente" @selected(old('estado', $appointment->estado) == 'pendiente')>Pendiente</option>
                                <option value="confirmada" @selected(old('estado', $appointment->estado) == 'confirmada')>Confirmada</option>
                                <option value="cancelada" @selected(old('estado', $appointment->estado) == 'cancelada')>Cancelada</option>
                                <option value="finalizada" @selected(old('estado', $appointment->estado) == 'finalizada')>Finalizada</option>
                            </select>
                            @error('estado')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        @php
                            $selectedServicios = old('servicios', $appointment->servicios->pluck('id')->toArray());
                            $selectedProductos = old('productos', $appointment->productos->pluck('id')->toArray());
                            $serviciosCantidad = old('servicios_cantidad', $appointment->servicios->pluck('pivot.cantidad', 'id')->toArray());
                            $productosCantidad = old('productos_cantidad', $appointment->productos->pluck('pivot.cantidad', 'id')->toArray());
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
                                <i class="fas fa-save mr-2"></i>Actualizar Cita
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 border-t pt-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Historial de estados</h2>
                        @if ($appointment->statusHistories->isEmpty())
                            <p class="text-sm text-gray-500">Sin cambios registrados.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($appointment->statusHistories->sortByDesc('created_at') as $history)
                                    <div class="flex flex-wrap items-center justify-between gap-3 border rounded-lg px-4 py-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $history->from_status ? ucfirst($history->from_status) : 'Inicio' }}
                                                <span class="text-gray-400">→</span>
                                                {{ ucfirst($history->to_status) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $history->created_at->format('d/m/Y h:i A') }}
                                                @if ($history->usuario)
                                                    · {{ $history->usuario->name }}
                                                @endif
                                            </p>
                                        </div>
                                        @if ($history->note)
                                            <span class="text-xs text-gray-600 bg-gray-100 rounded-full px-3 py-1">{{ $history->note }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadPets() {
            const clienteId = document.getElementById('cliente_id').value;
            const mascotaSelect = document.getElementById('mascotas');
            const selectedMascotas = @json($selectedMascotas).map(id => parseInt(id, 10));
            
            if (!clienteId) {
                mascotaSelect.innerHTML = '<option value="" disabled>Selecciona una o varias mascotas</option>';
                return;
            }

            // Fetch pets for this client
            fetch(`/api/clients/${clienteId}/pets`)
                .then(response => response.json())
                .then(data => {
                    let html = '<option value="" disabled>Selecciona una o varias mascotas</option>';
                    data.forEach(pet => {
                        const selected = selectedMascotas.includes(pet.id) ? 'selected' : '';
                        html += `<option value="${pet.id}" ${selected}>${pet.nombre} (${pet.especie})</option>`;
                    });
                    mascotaSelect.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    mascotaSelect.innerHTML = '<option value="" disabled>No se pudieron cargar mascotas</option>';
                });
        }

        // Load pets on page load
        document.addEventListener('DOMContentLoaded', loadPets);
    </script>
</x-app-layout>
