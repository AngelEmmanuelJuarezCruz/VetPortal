<?php
// Cargar Laravel y ejecutar el seeder
require_once __DIR__ . '/bootstrap/app.php';

$app = app();
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Ejecutar el seeder
$exitCode = $kernel->call('db:seed', [
    '--class' => 'TestAppointmentSeeder'
]);

exit($exitCode);
?>