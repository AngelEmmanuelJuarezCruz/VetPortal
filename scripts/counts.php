<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Veterinarios: ' . App\Models\Veterinario::count() . PHP_EOL;
echo 'Servicios: ' . App\Models\Servicio::count() . PHP_EOL;
