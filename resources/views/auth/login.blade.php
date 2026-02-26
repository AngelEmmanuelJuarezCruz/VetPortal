<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - VetPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- SECCIÓN IZQUIERDA: FORMULARIO DE LOGIN -->
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <div class="text-center mb-8">
                <i class="fas fa-paw text-blue-600 text-4xl mb-3"></i>
                <h1 class="text-3xl font-bold text-gray-800">VetPortal</h1>
                <p class="text-gray-600 mt-2">Gestiona tu clínica veterinaria de manera eficiente</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Recuérdame</label>
                    </div>
                    <a href="{{ route('forgot-password.form') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">¿Olvidé contraseña?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Iniciar Sesión
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">O continúa con</span>
                </div>
            </div>

            <a href="{{ route('auth.google') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.418 2.865 8.14 6.839 9.49.5.092.682-.217.682-.482 0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.031-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.378.203 2.398.1 2.651.64.7 1.03 1.595 1.03 2.688 0 3.848-2.338 4.695-4.566 4.943.359.308.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.001 10.001 0 0020 10c0-5.523-4.477-10-10-10z" clip-rule="evenodd" />
                </svg>
                Google
            </a>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">¿Eres nuevo? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Registra tu veterinaria</a></p>
            </div>
        </div>

        <!-- SECCIÓN DERECHA: CLÍNICAS REGISTRADAS -->
        <div class="lg:block hidden">
            <div class="bg-white p-8 rounded-lg shadow-xl h-full">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-hospital text-blue-600 mr-3"></i>Clínicas Registradas
                </h2>
                
                @if ($veterinarias->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">Aún no hay clínicas registradas</p>
                        <p class="text-gray-500 text-sm mt-2">Sé el primero en registrar tu clínica</p>
                    </div>
                @else
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach ($veterinarias as $vet)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4">
                                        <i class="fas fa-clinic-medical"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $vet->nombre }}</h3>
                                        <div class="text-sm text-gray-600 mt-2 space-y-1">
                                            <p><i class="fas fa-envelope mr-2 text-blue-500"></i>{{ $vet->correo }}</p>
                                            <p><i class="fas fa-phone mr-2 text-blue-500"></i>{{ $vet->telefono }}</p>
                                            @if ($vet->direccion)
                                                <p><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>{{ $vet->direccion }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>