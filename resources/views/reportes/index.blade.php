<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Reportes y Analítica</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($reportes as $key => $reporte)
                    <a href="{{ route($reporte['route']) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-t-4 border-blue-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="{{ $reporte['icon'] }} text-blue-600 text-3xl"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $reporte['title'] }}</h3>
                                <p class="text-gray-600 text-sm mt-2">{{ $reporte['description'] }}</p>
                                <span class="text-blue-600 hover:text-blue-800 font-medium text-sm mt-4 inline-block">Ver →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
