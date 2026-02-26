<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VetPortal - Gestión de Clínica Veterinaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-paw text-blue-600 text-2xl mr-3"></i>
                    <span class="text-2xl font-bold text-gray-900">VetPortal</span>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <h1 class="text-5xl font-bold text-gray-900 mb-6">
                    Gestiona tu clínica veterinaria de forma profesional
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    VetPortal te ayuda a organizar clientes, mascotas, veterinarios y citas en un solo lugar.
                </p>
                
                <!-- Features -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="text-gray-700">Gestión completa de clientes</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                            <i class="fas fa-paw"></i>
                        </div>
                        <span class="text-gray-700">Registro de mascotas y historial médico</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="text-gray-700">Sistema de citas inteligente</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <span class="text-gray-700">Administración de veterinarios</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex gap-4">
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
                        Comenzar Ahora
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 px-8 rounded-lg transition">
                        Iniciar Sesión
                    </a>
                </div>
            </div>

            <!-- Right Image -->
            <div class="hidden md:block">
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <div class="aspect-square bg-gradient-to-br from-blue-100 to-indigo-200 rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/AdobeStock_415479795-scaled.jpeg') }}" alt="VetPortal" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Por qué elegir VetPortal</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 text-center">
                    <div class="bg-blue-600 text-white p-4 rounded-full inline-block mb-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Seguro y Confiable</h3>
                    <p class="text-gray-600">Tus datos están protegidos con los más altos estándares de seguridad.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 text-center">
                    <div class="bg-green-600 text-white p-4 rounded-full inline-block mb-4">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Acceso Multiplataforma</h3>
                    <p class="text-gray-600">Accede desde cualquier dispositivo, en cualquier momento y lugar.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-8 text-center">
                    <div class="bg-indigo-600 text-white p-4 rounded-full inline-block mb-4">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Soporte 24/7</h3>
                    <p class="text-gray-600">Nuestro equipo está siempre disponible para ayudarte.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-12 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
            <p class="text-xl text-blue-100 mb-8">Crea una cuenta gratuita y gestiona tu clínica veterinaria hoy mismo.</p>
            <a href="{{ route('register') }}" class="bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-8 rounded-lg transition inline-block">
                Registrarse Gratis
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2026 VetPortal. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
