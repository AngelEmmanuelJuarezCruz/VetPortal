<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\VeterinariaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarouselController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\VeterinarianController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClinicalHistoryController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\MovimientoStockController;
use App\Http\Controllers\ReporteController;
use App\Models\Cliente;

// Redirigir la ruta principal según el estado de autenticación
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Tenant creation routes (require authentication but not tenant)
Route::middleware(['auth'])->group(function () {
    Route::resource('tenants', \App\Http\Controllers\TenantController::class)->except(['index', 'destroy']);
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'ensure.tenant'])->group(function () {
    // Rutas para Veterinarias
    Route::resource('veterinarias', VeterinariaController::class);

    // Rutas para Clientes
    Route::resource('clients', ClientController::class);
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('/api/clientes/por-veterinaria/{veterinariaId}', [ClienteController::class, 'porVeterinaria']);

    // Rutas para Mascotas
    Route::resource('mascotas', MascotaController::class)->except(['show']);
    Route::get('clientes/{cliente}/mascotas/create', [MascotaController::class, 'create'])->name('clientes.mascotas.create');
    Route::get('/mascotas/create', [MascotaController::class, 'create'])->name('mascotas.create.standalone');
    Route::get('/api/mascotas/por-especie', [MascotaController::class, 'porEspecie']);
    Route::get('/api/mascotas/por-veterinaria/{veterinariaId}', [MascotaController::class, 'porVeterinaria']);
    Route::get('mascotas/{mascota}', [MascotaController::class, 'show'])->name('mascotas.show');

    // Clinical History routes nested under pets
    Route::resource('pets.clinical-histories', ClinicalHistoryController::class)->except(['show', 'edit', 'update'])->middleware('auth');

    // Pet CRUD routes
    Route::resource('pets', PetController::class)->middleware('auth');

    // Veterinarian CRUD routes
    Route::resource('veterinarians', VeterinarianController::class)->middleware('auth');

    // Appointment CRUD routes
    Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('appointments/{appointment}/finalize', [AppointmentController::class, 'finalize'])->name('appointments.finalize');
    Route::post('appointments/{appointment}/reminder', [AppointmentController::class, 'reminder'])->name('appointments.reminder');
    Route::resource('appointments', AppointmentController::class)->middleware('auth');

    // Inventario y servicios
    Route::resource('productos', ProductoController::class)->middleware('auth');
    Route::resource('servicios', ServicioController::class)->middleware('auth');
    Route::resource('movimientos-stock', MovimientoStockController::class)->only(['index', 'create', 'store'])->middleware('auth');

    // Reportes
    Route::prefix('reportes')->name('reportes.')->middleware('auth')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('citas', [ReporteController::class, 'citas'])->name('citas');
        Route::get('citas/export/excel', [ReporteController::class, 'exportCitasExcel'])->name('citas.excel');
        Route::get('citas/export/pdf', [ReporteController::class, 'exportCitasPdf'])->name('citas.pdf');
        Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('consumo', [ReporteController::class, 'consumo'])->name('consumo');
        Route::get('servicios', [ReporteController::class, 'servicios'])->name('servicios');
        Route::get('ingresos', [ReporteController::class, 'ingresos'])->name('ingresos');
        Route::get('movimientos', [ReporteController::class, 'movimientos'])->name('movimientos');
    });

    // Route to get pets for a specific client (for dynamic dropdowns)
    Route::get('/api/clients/{client}/pets', function (Cliente $client) {
        $user = Auth::user();
        $tenantId = $user->tenant_id ?? $user->veterinaria_id;

        // Ensure the client belongs to the authenticated user's vet clinic
        if ($client->veterinaria_id !== $tenantId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($client->mascotas()->select('id', 'nombre', 'especie')->orderBy('nombre')->get());
    })->name('api.clients.pets')->middleware('auth');

    // Route to search clients by phone number
    Route::get('/api/clients/search-by-phone', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        $tenantId = $user->tenant_id ?? $user->veterinaria_id;
        $telefono = $request->query('telefono', '');

        if (empty($telefono)) {
            return response()->json([]);
        }

        $clients = Cliente::where('veterinaria_id', $tenantId)
            ->where('telefono', 'like', "%{$telefono}%")
            ->select('id', 'nombre', 'apellido', 'telefono', 'correo')
            ->orderBy('nombre')
            ->limit(10)
            ->get();

        return response()->json($clients);
    })->name('api.clients.search-by-phone')->middleware('auth');

    // Dashboard/Estadísticas
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $veterinariaId = $user->tenant_id;

        $stats = [
            'clients' => \App\Models\Cliente::where('veterinaria_id', $veterinariaId)->count(),
            'pets' => \App\Models\Mascota::where('veterinaria_id', $veterinariaId)->count(),
            'appointments_today' => \App\Models\Cita::where('veterinaria_id', $veterinariaId)->whereDate('fecha', today())->count(),
            'active_veterinarians' => \App\Models\Veterinario::where('veterinaria_id', $veterinariaId)->where('activo', true)->count(),
        ];

        $recent_pets = \App\Models\Mascota::where('veterinaria_id', $veterinariaId)
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $low_stock_products = \App\Models\Producto::where('veterinaria_id', $veterinariaId)
            ->where('stock_actual', '<=', \DB::raw('stock_minimo'))
            ->orderBy('stock_actual')
            ->limit(5)
            ->get();

        $expiring_products = \App\Models\Producto::where('veterinaria_id', $veterinariaId)
            ->whereNotNull('fecha_caducidad')
            ->whereBetween('fecha_caducidad', [today(), today()->addDays(30)])
            ->orderBy('fecha_caducidad')
            ->limit(5)
            ->get();

        $total_inventory_value = \App\Models\Producto::where('veterinaria_id', $veterinariaId)
            ->sum(\DB::raw('stock_actual * precio_venta'));

        $top_services = \App\Models\Cita::where('veterinaria_id', $veterinariaId)
            ->with('servicios')
            ->whereBetween('fecha', [today()->subDays(30), today()])
            ->get()
            ->flatMap(fn($cita) => $cita->servicios)
            ->groupBy('id')
            ->map(fn($servicios) => [
                'servicio' => $servicios->first(),
                'cantidad' => $servicios->count(),
                'ingreso' => $servicios->sum('pivot.cantidad') * $servicios->first()->precio,
            ])
            ->sortByDesc('cantidad')
            ->take(5);

        $monthly_revenue = \App\Models\Cita::where('veterinaria_id', $veterinariaId)
            ->with('servicios', 'productos')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->get()
            ->reduce(function($total, $cita) {
                $servicios_total = $cita->servicios->sum(fn($s) => $s->pivot->cantidad * $s->precio);
                return $total + $servicios_total;
            }, 0);

        $stats['low_stock_count'] = $low_stock_products->count();
        $stats['expiring_count'] = $expiring_products->count();
        $stats['inventory_value'] = $total_inventory_value;
        $stats['monthly_revenue'] = $monthly_revenue;

        return view('dashboard', compact('stats', 'recent_pets', 'low_stock_products', 'expiring_products', 'top_services'));
    })->name('dashboard');
});
// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Password Recovery Routes...
Route::get('forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password.form');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendPassword'])->name('forgot-password.send');

// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Google OAuth routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Panel de administración
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('admin.configuracion');
    Route::post('/configuracion', [AdminController::class, 'saveConfiguracion'])->name('admin.save-configuracion');
    
    // Gestión de carrusel
    Route::resource('carousel', CarouselController::class);
    
    // Cambiar modo oscuro
    Route::post('/toggle-dark-mode', [AdminController::class, 'toggleDarkMode'])->name('toggle-dark-mode');
});

// Nota: las rutas administrativas quedan en el grupo `admin` más arriba
Route::get('/api/clientes/{cliente}/veterinaria', function (Cliente $cliente) {
    return response()->json(['veterinaria_id' => $cliente->veterinaria_id]);
})->name('api.clientes.veterinaria');
Route::get('/api/veterinarias/{veterinaria}/clientes', function (Veterinaria $veterinaria) {
    $clientes = $veterinaria->clientes()->select('id', 'nombre_completo')->get();
    return response()->json(['clientes' => $clientes]);
})->name('api.veterinarias.clientes');

// Ruta temporary para testing - ejecutar seeder de prueba
Route::get('/debug/seed-test-appointment', function () {
    if (!Auth::check()) {
        return response('Debe estar autenticado', 401);
    }
    
    try {
        Artisan::call('db:seed', ['--class' => 'TestAppointmentSeeder']);
        return response('✓ Seeder ejecutado correctamente. Verifica el archivo storage/logs/laravel.log para ver el email de recordatorio.', 200);
    } catch (\Exception $e) {
        return response('Error: ' . $e->getMessage(), 500);
    }
});

Route::get('/profile', function () {
    $user = Auth::user();
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return view('profile', compact('user'));
})->name('profile');