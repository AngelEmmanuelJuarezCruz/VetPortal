<?php

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Cita;
use App\Models\Veterinario;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get first user with tenant
$user = User::whereNotNull('tenant_id')->first();

if (!$user) {
    echo "❌ No authenticated user with a tenant found.\n";
    exit(1);
}

$veterinariaId = $user->tenant_id;
echo "✓ Using tenant: {$user->tenant->nombre} (ID: $veterinariaId)\n";

// Create client
$email = 'ac5892496@gmail.com';
$phone = '833 181 8600';

echo "\nCreating client...\n";
$client = Cliente::create([
    'uuid' => (string) Str::uuid(),
    'veterinaria_id' => $veterinariaId,
    'nombre' => 'Juan',
    'apellido' => 'Pérez',
    'telefono' => $phone,
    'correo' => $email,
]);

echo "✓ Client created (ID: {$client->id}): {$client->nombre} {$client->apellido}\n";
echo "  Email: {$client->correo}\n";
echo "  Phone: {$client->telefono}\n";

// Create 3 pets
echo "\nCreating 3 pets...\n";
$petNames = ['Max', 'Luna', 'Buddy'];
$petSpecies = ['Perro', 'Gato', 'Perro'];
$petBreeds = ['Labrador', 'Siames', 'Golden Retriever'];
$petColors = ['Negro', 'Blanco', 'Dorado'];

$pets = [];
foreach (range(0, 2) as $index) {
    $pet = Mascota::create([
        'cliente_id' => $client->id,
        'veterinaria_id' => $veterinariaId,
        'nombre' => $petNames[$index],
        'especie' => $petSpecies[$index],
        'raza' => $petBreeds[$index],
        'color' => $petColors[$index],
        'fecha_nacimiento' => now()->subYears(rand(1, 5)),
        'descripcion' => "Mascota de prueba para {$client->nombre}",
    ]);

    $pets[] = $pet;
    echo "  ✓ {$pet->nombre} ({$pet->especie}) - ID: {$pet->id}\n";
}

// Get or create veterinarian
echo "\nGetting veterinarian...\n";
$veterinarian = Veterinario::where('veterinaria_id', $veterinariaId)->first();

if (!$veterinarian) {
    echo "  Creating new veterinarian...\n";
    $veterinarian = Veterinario::create([
        'veterinaria_id' => $veterinariaId,
        'nombre' => 'Dr. García',
        'apellido' => 'Veterinario',
        'telefono' => '555-1234',
        'especializacion' => 'General',
    ]);
    echo "  ✓ New veterinarian created: {$veterinarian->nombre} {$veterinarian->apellido}\n";
} else {
    echo "  ✓ Using existing: {$veterinarian->nombre} {$veterinarian->apellido}\n";
}

// Create appointment
echo "\nCreating appointment...\n";
$appointmentDate = now()->addDay()->format('Y-m-d');
$appointmentTime = '10:00';

$appointment = Cita::create([
    'veterinaria_id' => $veterinariaId,
    'cliente_id' => $client->id,
    'mascota_id' => $pets[0]->id,
    'veterinario_id' => $veterinarian->id,
    'user_id' => $user->id,
    'fecha' => $appointmentDate,
    'hora' => $appointmentTime,
    'motivo' => 'Chequeo de rutina y prueba de recordatorio de email',
    'estado' => 'pendiente',
]);

$appointment->mascotas()->sync([$pets[0]->id]);

echo "✓ Appointment created (ID: {$appointment->id})\n";
echo "  Pet: {$appointment->mascota->nombre}\n";
echo "  Date: {$appointment->fecha}\n";
echo "  Time: {$appointment->hora}\n";
echo "  Veterinarian: {$veterinarian->nombre} {$veterinarian->apellido}\n";
echo "  Reason: {$appointment->motivo}\n";

// Send reminder email
echo "\nSending reminder email...\n";
try {
    Mail::to($email)->send(new AppointmentReminder($appointment));
    echo "✓ Email sent successfully!\n";
    echo "\n📧 Mail Configuration:\n";
    echo "   Mailer: " . config('mail.default') . "\n";
    if (config('mail.default') === 'log') {
        echo "   🔍 Check storage/logs/laravel.log for email content\n";
    }
} catch (\Exception $e) {
    echo "❌ Error sending email: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "TEST APPOINTMENT CREATED SUCCESSFULLY!\n";
echo str_repeat("=", 60) . "\n";
echo "\nSummary:\n";
echo "  Client ID: {$client->id}\n";
echo "  Pets Created: 3\n";
echo "  Appointment ID: {$appointment->id}\n";
echo "  Email Sent To: $email\n";
echo "\n✓ Ready to test the appointment reminder functionality!\n";
