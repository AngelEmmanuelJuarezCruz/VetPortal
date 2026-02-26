<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - VetPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <div class="text-center mb-8">
                <a href="{{ route('login') }}" class="inline-block mb-4">
                    <i class="fas fa-arrow-left text-gray-600 hover:text-blue-600 text-xl"></i>
                </a>
                <i class="fas fa-lock text-blue-600 text-4xl mb-3 block"></i>
                <h1 class="text-2xl font-bold text-gray-800">Recuperar Contraseña</h1>
                <p class="text-gray-600 mt-2 text-sm">Ingresa tu correo para recibir una contraseña temporal</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password.send') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="tu@correo.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-2">Ingresa el correo del usuario o de la veterinaria registrada</p>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Contraseña por Correo
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya recuerdas tu contraseña? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Inicia sesión</a>
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-white bg-opacity-50 backdrop-blur px-6 py-4 rounded-lg border border-white border-opacity-20">
            <p class="text-xs text-gray-700 text-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Puedes usar tu correo personal o el correo registrado de tu clínica. Te enviaremos una contraseña temporal por correo.
            </p>
        </div>
    </div>
</body>
</html>
