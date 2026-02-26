<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Citas</h1>
                    <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-calendar-plus mr-2"></i>Agendar Cita
                    </a>
                </div>
            </div>

            @php
                $statusLabels = [
                    'pendiente' => 'Pendiente',
                    'confirmada' => 'Confirmada',
                    'cancelada' => 'Cancelada',
                    'finalizada' => 'Finalizada',
                ];
            @endphp

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('whatsapp_message'))
                <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <p class="font-semibold">Mensaje para WhatsApp</p>
                            <textarea id="whatsappMessage" class="mt-2 w-full px-3 py-2 border border-amber-200 rounded-md text-sm" rows="3" readonly>{{ session('whatsapp_message') }}</textarea>
                        </div>
                        <button type="button" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-md" onclick="copyWhatsappMessage()">
                            Copiar
                        </button>
                    </div>
                </div>
            @endif

            <!-- Dashboard Panels -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Hoy</h2>
                    <div class="space-y-2">
                        @forelse ($todayAppointments as $appointment)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }} - {{ $appointment->motivo }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $statusLabels[$appointment->estado] ?? $appointment->estado }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay citas hoy.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Manana</h2>
                    <div class="space-y-2">
                        @forelse ($tomorrowAppointments as $appointment)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }} - {{ $appointment->motivo }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $statusLabels[$appointment->estado] ?? $appointment->estado }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay citas para manana.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Pasado manana</h2>
                    <div class="space-y-2">
                        @forelse ($dayAfterAppointments as $appointment)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }} - {{ $appointment->motivo }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $statusLabels[$appointment->estado] ?? $appointment->estado }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay citas para pasado manana.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Citas proximas (hoy)</h2>
                    <div class="space-y-2">
                        @forelse ($upcomingToday as $appointment)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }} - {{ $appointment->motivo }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $statusLabels[$appointment->estado] ?? $appointment->estado }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay citas proximas hoy.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Citas pasadas (hoy)</h2>
                    <div class="space-y-2">
                        @forelse ($pastToday as $appointment)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }} - {{ $appointment->motivo }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $statusLabels[$appointment->estado] ?? $appointment->estado }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay citas pasadas hoy.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('appointments.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="search" placeholder="Buscar por cliente, mascota, motivo..." value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select name="estado" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Estado</option>
                            <option value="pendiente" @selected(request('estado') == 'pendiente')>Pendiente</option>
                            <option value="confirmada" @selected(request('estado') == 'confirmada')>Confirmada</option>
                            <option value="cancelada" @selected(request('estado') == 'cancelada')>Cancelada</option>
                            <option value="finalizada" @selected(request('estado') == 'finalizada')>Finalizada</option>
                        </select>
                        <input type="date" name="fecha" value="{{ request('fecha') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="flex gap-3 md:col-span-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                            <a href="{{ route('appointments.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente y Mascota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veterinario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($appointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->cliente->nombre }} {{ $appointment->cliente->apellido }}</div>
                                    @php
                                        $mascotas = $appointment->mascotas->pluck('nombre')->filter()->values();
                                        if ($mascotas->isEmpty() && $appointment->mascota) {
                                            $mascotas = collect([$appointment->mascota->nombre]);
                                        }
                                    @endphp
                                    <div class="text-sm text-gray-600">
                                        {{ $mascotas->isEmpty() ? 'Sin mascota' : $mascotas->join(', ') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->fecha)->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $appointment->veterinario->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs truncate">
                                    {{ $appointment->motivo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'confirmada' => 'bg-blue-100 text-blue-800',
                                            'cancelada' => 'bg-red-100 text-red-800',
                                            'finalizada' => 'bg-green-100 text-green-800',
                                        ][$appointment->estado] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel = $statusLabels[$appointment->estado] ?? $appointment->estado;
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>
                                    @if ($appointment->estado === 'pendiente')
                                        <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check mr-1"></i>Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    @if (in_array($appointment->estado, ['pendiente', 'confirmada']))
                                        <form action="{{ route('appointments.reminder', $appointment) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            <button type="submit" class="text-amber-600 hover:text-amber-900">
                                                <i class="fas fa-comment mr-1"></i>Recordatorio
                                            </button>
                                        </form>
                                    @endif
                                    @if ($appointment->estado === 'confirmada')
                                        <form action="{{ route('appointments.finalize', $appointment) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-900">
                                                <i class="fas fa-flag-checkered mr-1"></i>Finalizar
                                            </button>
                                        </form>
                                    @endif
                                    @if (!in_array($appointment->estado, ['cancelada', 'finalizada']))
                                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-ban mr-1"></i>Cancelar
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro?');">
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
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron citas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>

    <script>
        function copyWhatsappMessage() {
            const textarea = document.getElementById('whatsappMessage');
            if (!textarea) {
                return;
            }
            textarea.select();
            document.execCommand('copy');
        }

        setInterval(() => {
            window.location.reload();
        }, 60000);
    </script>
</x-app-layout>
