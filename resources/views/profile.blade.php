<x-app-layout>
    <div class="py-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mi Perfil</h1>
            <p class="text-gray-600 mt-2">Administra tu información personal y configuración de cuenta</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Profile Header -->
                    <div class="h-32 bg-gradient-to-r from-purple-600 to-blue-600"></div>

                    <!-- Profile Info -->
                    <div class="px-6 pb-6 -mt-12 relative z-10">
                        <!-- Avatar -->
                        <div class="flex justify-center mb-4">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full border-4 border-white flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="text-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                            <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Stats -->
                        <div class="space-y-2 border-y py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Clientes registrados:</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Cliente::where('veterinaria_id', Auth::user()->tenant_id)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Mascotas:</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Mascota::where('veterinaria_id', Auth::user()->tenant_id)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Citas agendadas:</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Cita::where('veterinaria_id', Auth::user()->tenant_id)->count() }}</span>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500">
                                Miembro desde <span class="font-semibold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                    <div class="space-y-2">
                        <a href="{{ route('clients.create') }}" class="flex items-center space-x-3 px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg transition">
                            <i class="fas fa-user-plus"></i>
                            <span>Nuevo Cliente</span>
                        </a>
                        <a href="{{ route('pets.create') }}" class="flex items-center space-x-3 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition">
                            <i class="fas fa-paw"></i>
                            <span>Nueva Mascota</span>
                        </a>
                        <a href="{{ route('appointments.create') }}" class="flex items-center space-x-3 px-4 py-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Agendar Cita</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Settings -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center space-x-2">
                        <i class="fas fa-user text-purple-600"></i>
                        <span>Información Personal</span>
                    </h3>

                    <form method="POST" action="#" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Clinic Info -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mi Clínica</label>
                            @if(Auth::user()->tenant)
                                <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gradient-to-r from-purple-50 to-blue-50">
                                    <p class="font-semibold text-gray-900">{{ Auth::user()->tenant->nombre }}</p>
                                    <p class="text-sm text-gray-600">UUID: {{ substr(Auth::user()->tenant->uuid, 0, 8) }}...</p>
                                </div>
                            @else
                                <div class="w-full px-4 py-3 border border-dashed border-gray-300 rounded-lg bg-gray-50 text-center">
                                    <p class="text-gray-600 mb-2">Aún no has creado una clínica veterinaria</p>
                                    <a href="{{ route('tenants.create') }}" class="text-purple-600 hover:text-purple-700 font-semibold">Crear clinica</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center space-x-2">
                        <i class="fas fa-lock text-blue-600"></i>
                        <span>Seguridad</span>
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">Cambiar Contraseña</p>
                                <p class="text-sm text-gray-600">Actualiza tu contraseña regularmente</p>
                            </div>
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                                Cambiar
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">Sesiones Activas</p>
                                <p class="text-sm text-gray-600">Administra tus dispositivos conectados</p>
                            </div>
                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                                Ver Sesiones
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center space-x-2">
                        <i class="fas fa-bell text-yellow-600"></i>
                        <span>Notificaciones</span>
                    </h3>

                    <div class="space-y-4">
                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                            <input type="checkbox" checked class="w-4 h-4 text-purple-600 rounded">
                            <div>
                                <p class="font-medium text-gray-900">Recordatorios de Citas</p>
                                <p class="text-sm text-gray-600">Recibe notificaciones de citas próximas</p>
                            </div>
                        </label>

                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                            <input type="checkbox" checked class="w-4 h-4 text-purple-600 rounded">
                            <div>
                                <p class="font-medium text-gray-900">Nuevos Clientes</p>
                                <p class="text-sm text-gray-600">Notificaciones cuando se registran nuevos clientes</p>
                            </div>
                        </label>

                        <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                            <input type="checkbox" class="w-4 h-4 text-purple-600 rounded">
                            <div>
                                <p class="font-medium text-gray-900">Boletín Semanal</p>
                                <p class="text-sm text-gray-600">Resumen semanal de actividad</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-8">
                    <h3 class="text-lg font-bold text-red-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Zona de Peligro</span>
                    </h3>

                    <p class="text-sm text-red-800 mb-4">Las acciones siguientes no se pueden deshacer.</p>

                    <div class="space-y-3">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión en Todos los Dispositivos
                            </button>
                        </form>

                        <button class="w-full px-4 py-2 border-2 border-red-600 text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                            <i class="fas fa-trash mr-2"></i>Eliminar Cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
