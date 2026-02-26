<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

$app = app();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = $argv[1] ?? '';
if ($email === '') {
    echo "Usage: php scripts/whoami-tenant.php email@example.com\n";
    exit(1);
}

$user = App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "user not found\n";
    exit(1);
}

echo "user_id={$user->id} tenant_id={$user->tenant_id}\n";
