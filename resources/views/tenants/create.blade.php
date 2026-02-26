<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Clínica Veterinaria - VetPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">VetPortal</h1>
                <h2 class="mt-6 text-2xl font-bold text-gray-900">Crear tu Clínica Veterinaria</h2>
                <p class="mt-2 text-gray-600">Completa la información de tu clínica para comenzar</p>
            </div>

            <form method="POST" action="{{ route('tenants.store') }}" class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                        <strong class="font-bold">¡Error!</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Nombre de la Clinica -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Clinica</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electronico</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Telefono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Direccion (Opcional)</label>
                    <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Tema -->
                <div>
                    <label for="tema" class="block text-sm font-medium text-gray-700">Tema</label>
                    <select id="tema" name="tema" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="claro" {{ old('tema', 'claro') === 'claro' ? 'selected' : '' }}>Claro</option>
                        <option value="oscuro" {{ old('tema') === 'oscuro' ? 'selected' : '' }}>Oscuro</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        Crear Clínica
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
