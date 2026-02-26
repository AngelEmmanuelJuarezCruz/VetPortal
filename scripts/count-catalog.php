<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

$app = app();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenantId = (int)($argv[1] ?? 0);
if ($tenantId <= 0) {
    echo "Usage: php scripts/count-catalog.php TENANT_ID\n";
    exit(1);
}

$productos = App\Models\Producto::where('tenant_id', $tenantId)->count();
$servicios = App\Models\Servicio::where('tenant_id', $tenantId)->count();

echo "productos={$productos} servicios={$servicios}\n";
