<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminder;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Veterinario;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateTestAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:appointment {--email=ac5892496@gmail.com} {--phone=833 181 8600}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test client with 3 pets and schedule an appointment to test reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $phone = $this->option('phone');

        // Get the first authenticated tenant/user
        $user = User::whereNotNull('tenant_id')->first();
        
        if (!$user) {
            $this->error('No authenticated user with a tenant found. Please create a user first.');
            return 1;
        }

        $veterinariaId = $user->tenant_id;
        $this->info("Using tenant: {$user->tenant->nombre ?? 'Unknown'} (ID: $veterinariaId)");

        // Create client
        $this->info("Creating client with email: $email and phone: $phone");
        
        $client = Cliente::create([
            'uuid' => (string) Str::uuid(),
            'veterinaria_id' => $veterinariaId,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => $phone,
            'correo' => $email,
        ]);

        $this->info("✓ Client created with ID: {$client->id}");

        // Create 3 pets
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
            $this->info("✓ Pet created: {$pet->nombre} ({$pet->especie}) with ID: {$pet->id}");
        }

        // Get or create a veterinarian
        $veterinarian = Veterinario::where('veterinaria_id', $veterinariaId)->first();
        
        if (!$veterinarian) {
            $this->warn("No veterinarian found for this clinic. Creating one...");
            $veterinarian = Veterinario::create([
                'veterinaria_id' => $veterinariaId,
                'nombre' => 'Dr. García',
                'apellido' => 'Veterinario',
                'telefono' => '555-1234',
                'especializacion' => 'General',
            ]);
            $this->info("✓ Veterinarian created with ID: {$veterinarian->id}");
        } else {
            $this->info("Using existing veterinarian: {$veterinarian->nombre} {$veterinarian->apellido}");
        }

        // Create appointment for tomorrow at 10:00 AM
        $appointmentDate = now()->addDay()->format('Y-m-d');
        $appointmentTime = '10:00';

        $this->info("Creating appointment for {$pets[0]->nombre} on $appointmentDate at $appointmentTime");

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

        $this->info("✓ Appointment created with ID: {$appointment->id}");

        // Send reminder email
        $this->info("\nSending reminder email to: $email");
        
        try {
            Mail::to($email)->send(new AppointmentReminder($appointment));
            $this->info("✓ Reminder email sent successfully!");
            $this->line("\nEmail Configuration:");
            $this->line("- Mailer: " . config('mail.default'));
            $this->line("- If using 'log' mailer, check storage/logs/laravel.log for email content");
        } catch (\Exception $e) {
            $this->error("✗ Error sending email: " . $e->getMessage());
        }

        // Summary
        $this->info("\n" . str_repeat("=", 50));
        $this->info("TEST APPOINTMENT CREATED SUCCESSFULLY!");
        $this->info(str_repeat("=", 50));
        $this->line("Client Details:");
        $this->line("  Name: {$client->nombre} {$client->apellido}");
        $this->line("  Email: {$client->correo}");
        $this->line("  Phone: {$client->telefono}");
        $this->line("\nPets Created: " . count($pets));
        foreach ($pets as $pet) {
            $this->line("  - {$pet->nombre} ({$pet->especie})");
        }
        $this->line("\nAppointment Details:");
        $this->line("  Pet: {$appointment->mascota->nombre}");
        $this->line("  Date: {$appointment->fecha}");
        $this->line("  Time: {$appointment->hora}");
        $this->line("  Veterinarian: {$veterinarian->nombre} {$veterinarian->apellido}");
        $this->line("  Reason: {$appointment->motivo}");
        $this->line("\nTo verify the email was sent:");
        $this->line("  1. Check storage/logs/laravel.log");
        $this->line("  2. Look for 'Recordatorio de cita' email content");

        return 0;
    }
}
