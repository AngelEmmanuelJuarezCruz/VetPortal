<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">Editar Producto</h1>
                    <p class="text-gray-600 mb-6">Actualiza la informacion del producto.</p>

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

                    <form action="{{ route('productos.update', $producto) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                <select name="tipo" id="tipo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                    <option value="medicamento" @selected(old('tipo', $producto->tipo) == 'medicamento')>Medicamento</option>
                                    <option value="articulo" @selected(old('tipo', $producto->tipo) == 'articulo')>Articulo</option>
                                    <option value="alimento" @selected(old('tipo', $producto->tipo) == 'alimento')>Alimento</option>
                                </select>
                            </div>
                            <div>
                                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                                <input type="text" name="categoria" id="categoria" value="{{ old('categoria', $producto->categoria) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripcion</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="stock_actual" class="block text-sm font-medium text-gray-700">Stock Actual</label>
                                <input type="number" min="0" name="stock_actual" id="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Minimo</label>
                                <input type="number" min="0" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="precio_compra" class="block text-sm font-medium text-gray-700">Precio Compra</label>
                                <input type="number" min="0" step="0.01" name="precio_compra" id="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="precio_venta" class="block text-sm font-medium text-gray-700">Precio Venta</label>
                                <input type="number" min="0" step="0.01" name="precio_venta" id="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="proveedor" class="block text-sm font-medium text-gray-700">Proveedor</label>
                                <input type="text" name="proveedor" id="proveedor" value="{{ old('proveedor', $producto->proveedor) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="fecha_caducidad" class="block text-sm font-medium text-gray-700">Fecha Caducidad</label>
                                <input type="date" name="fecha_caducidad" id="fecha_caducidad" value="{{ old('fecha_caducidad', optional($producto->fecha_caducidad)->format('Y-m-d')) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t">
                            <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
