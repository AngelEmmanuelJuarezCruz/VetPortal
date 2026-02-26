<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Registrar Movimiento</h1>
                    <p class="text-gray-600 mb-6">Ingresa una entrada o salida de stock.</p>

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

                    <form action="{{ route('movimientos-stock.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto *</label>
                            <select name="producto_id" id="producto_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Selecciona un producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" @selected(old('producto_id') == $producto->id)>
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock_actual }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                <select name="tipo" id="tipo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                    <option value="entrada" @selected(old('tipo') == 'entrada')>Entrada</option>
                                    <option value="salida" @selected(old('tipo') == 'salida')>Salida</option>
                                </select>
                            </div>
                            <div>
                                <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad *</label>
                                <input type="number" min="1" name="cantidad" id="cantidad" value="{{ old('cantidad') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo *</label>
                                <input type="text" name="motivo" id="motivo" value="{{ old('motivo') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="referencia" class="block text-sm font-medium text-gray-700">Referencia</label>
                                <input type="text" name="referencia" id="referencia" value="{{ old('referencia') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="datetime-local" name="fecha" id="fecha" value="{{ old('fecha') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('movimientos-stock.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
