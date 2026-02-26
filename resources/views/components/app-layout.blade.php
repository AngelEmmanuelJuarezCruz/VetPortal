<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VetPortal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'w-20': sidebarCollapsed, 'w-64': !sidebarCollapsed }" class="fixed inset-y-0 left-0 z-30 bg-gradient-to-b from-gray-900 to-gray-800 transform lg:translate-x-0 lg:static lg:inset-0 transition-all duration-300 ease-in-out overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-4 border-b border-gray-700 sticky top-0 bg-gray-900">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-white hover:text-purple-400 transition">
                    <i class="fas fa-paw text-purple-400 text-xl"></i>
                    <span x-show="!sidebarCollapsed" class="font-bold text-lg">VetPortal</span>
                </a>
                <!-- Toggle Sidebar Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block text-gray-400 hover:text-white transition">
                    <i :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'" class="fas"></i>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 py-6 px-3 space-y-2">
                <!-- Dashboard -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition group {{ request()->routeIs('dashboard') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('dashboard') }}" :title="sidebarCollapsed ? 'Dashboard' : ''">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span x-show="!sidebarCollapsed">Dashboard</span>
                </a>

                <!-- Clientes -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('clients.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('clients.index') }}" :title="sidebarCollapsed ? 'Clientes' : ''">
                    <i class="fas fa-users w-5"></i>
                    <span x-show="!sidebarCollapsed">Clientes</span>
                </a>

                <!-- Mascotas -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('pets.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('pets.index') }}" :title="sidebarCollapsed ? 'Mascotas' : ''">
                    <i class="fas fa-paw w-5"></i>
                    <span x-show="!sidebarCollapsed">Mascotas</span>
                </a>

                <!-- Veterinarios -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('veterinarians.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('veterinarians.index') }}" :title="sidebarCollapsed ? 'Veterinarios' : ''">
                    <i class="fas fa-user-md w-5"></i>
                    <span x-show="!sidebarCollapsed">Veterinarios</span>
                </a>

                <!-- Citas -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('appointments.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('appointments.index') }}" :title="sidebarCollapsed ? 'Citas' : ''">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span x-show="!sidebarCollapsed">Citas</span>
                </a>

                <!-- Productos -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('productos.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('productos.index') }}" :title="sidebarCollapsed ? 'Productos' : ''">
                    <i class="fas fa-boxes w-5"></i>
                    <span x-show="!sidebarCollapsed">Productos</span>
                </a>

                <!-- Servicios -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('servicios.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('servicios.index') }}" :title="sidebarCollapsed ? 'Servicios' : ''">
                    <i class="fas fa-briefcase-medical w-5"></i>
                    <span x-show="!sidebarCollapsed">Servicios</span>
                </a>

                <!-- Reportes -->
                <a class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('reportes.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" href="{{ route('reportes.index') }}" :title="sidebarCollapsed ? 'Reportes' : ''">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span x-show="!sidebarCollapsed">Reportes</span>
                </a>

            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-gray-700 p-4" x-show="!sidebarCollapsed">
                <div class="text-xs text-gray-400">
                    <p class="font-semibold">VetPortal v1.0</p>
                    <p class="mt-1">Sistema de Gestión Veterinaria</p>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
                <!-- Top Navigation Bar -->
                <div class="flex justify-between items-center px-6 py-4">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <i class="fas fa-paw text-purple-600 text-2xl"></i>
                        <span class="text-2xl font-bold text-gray-800">VetPortal</span>
                    </a>

                    <!-- Center Navigation -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <!-- Botones removidos -->
                    </nav>

                    <!-- Right Side: Wishlist, Mobile Toggle, Profile -->
                    <div class="flex items-center space-x-4">
                        <!-- Wishlist Button -->
                        <button class="text-gray-500 hover:text-purple-600 relative" title="Favoritos">
                            <i class="fas fa-heart text-2xl"></i>
                            <span class="absolute top-0 right-0 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </button>

                        <!-- Mobile Menu Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button @click="profileOpen = !profileOpen" class="flex items-center space-x-3 focus:outline-none hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:text-gray-700 sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileOpen" @click.away="profileOpen = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-2xl overflow-hidden z-50" style="display: none;">
                                <!-- User Info -->
                                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-4 py-3 border-b">
                                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Menu Items -->
                                <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-user w-5"></i>
                                    <span>Mi Perfil</span>
                                </a>
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-tachometer-alt w-5"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-cog w-5"></i>
                                    <span>Configuración</span>
                                </a>

                                <!-- Divider -->
                                <div class="border-t"></div>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
