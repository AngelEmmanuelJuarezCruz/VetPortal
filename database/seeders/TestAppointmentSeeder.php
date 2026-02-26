<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Cita;
use App\Models\Veterinario;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestAppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener primer usuario con tenant
        $user = User::whereNotNull('tenant_id')->first();

        if (!$user) {
            echo "Error: No hay usuario con tenant\n";
            return;
        }

        $veterinariaId = $user->tenant_id;

        // Crear cliente
        $client = Cliente::firstOrCreate(
            [
                'veterinaria_id' => $veterinariaId,
                'correo' => 'ac5892496@gmail.com',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'telefono' => '833 181 8600',
            ]
        );

        echo "✓ Cliente creado: {$client->nombre} {$client->apellido}\n";
        echo "  Email: {$client->correo}\n";
        echo "  Teléfono: {$client->telefono}\n\n";

        // Crear 3 mascotas
        $pets = [
            ['Max', 'Perro', 'Labrador', 'Negro'],
            ['Luna', 'Gato', 'Siames', 'Blanco'],
            ['Buddy', 'Perro', 'Golden Retriever', 'Dorado']
        ];

        $petIds = [];
        echo "✓ Mascotas creadas:\n";

        foreach ($pets as $pet) {
            $mascota = Mascota::create([
                'cliente_id' => $client->id,
                'veterinaria_id' => $veterinariaId,
                'nombre' => $pet[0],
                'especie' => $pet[1],
                'raza' => $pet[2],
                'color' => $pet[3],
                'fecha_nacimiento' => now()->subYears(rand(1, 5)),
                'descripcion' => "Mascota de prueba para {$client->nombre}",
            ]);
            
            $petIds[] = $mascota->id;
            echo "  - {$mascota->nombre} ({$mascota->especie})\n";
        }

        // Obtener o crear veterinario
        $vet = Veterinario::where('veterinaria_id', $veterinariaId)->first();

        if (!$vet) {
            $vet = Veterinario::create([
                'veterinaria_id' => $veterinariaId,
                'nombre' => 'Dr. García',
                'apellido' => 'Sánchez',
                'telefono' => '555-1234',
                'especializacion' => 'General',
            ]);
            echo "\n✓ Veterinario creado: Dr. García\n";
        } else {
            echo "\n✓ Veterinario existente usado\n";
        }

        // Crear cita para mañana a las 10:00
        $appointment = Cita::create([
            'veterinaria_id' => $veterinariaId,
            'cliente_id' => $client->id,
            'mascota_id' => $petIds[0],
            'veterinario_id' => $vet->id,
            'user_id' => $user->id,
            'fecha' => now()->addDay()->format('Y-m-d'),
            'hora' => '10:00',
            'motivo' => 'Chequeo de rutina y prueba de recordatorio de email',
            'estado' => 'pendiente',
        ]);

        // Relacionar mascota con cita
        $appointment->mascotas()->sync([$petIds[0]]);

        echo "\n✓ Cita agendada:\n";
        echo "  Mascota: Max\n";
        echo "  Fecha: " . now()->addDay()->format('Y-m-d') . "\n";
        echo "  Hora: 10:00\n";
        echo "  Estado: pendiente\n";

        // Preparar servicios y productos para citas historicas
        $servicios = Servicio::where('veterinaria_id', $veterinariaId)->take(2)->get();
        $productos = Producto::where('veterinaria_id', $veterinariaId)->take(2)->get();

        $serviciosSync = $servicios->mapWithKeys(fn($servicio, $index) => [
            $servicio->id => ['cantidad' => $index + 1],
        ])->all();

        $productosSync = $productos->mapWithKeys(fn($producto, $index) => [
            $producto->id => ['cantidad' => $index + 1],
        ])->all();

        // Crear citas historicas para probar rangos de fechas
        $pastAppointments = [
            ['fecha' => now()->subDays(45)->format('Y-m-d'), 'hora' => '09:00', 'estado' => 'finalizada'],
            ['fecha' => now()->subDays(20)->format('Y-m-d'), 'hora' => '13:30', 'estado' => 'confirmada'],
            ['fecha' => now()->subDays(7)->format('Y-m-d'), 'hora' => '16:00', 'estado' => 'cancelada'],
        ];

        foreach ($pastAppointments as $index => $data) {
            $mascotaId = $petIds[$index % count($petIds)];

            $pastAppointment = Cita::create([
                'veterinaria_id' => $veterinariaId,
                'cliente_id' => $client->id,
                'mascota_id' => $mascotaId,
                'veterinario_id' => $vet->id,
                'user_id' => $user->id,
                'fecha' => $data['fecha'],
                'hora' => $data['hora'],
                'motivo' => 'Cita historica para validar reportes',
                'estado' => $data['estado'],
            ]);

            $pastAppointment->mascotas()->sync([$mascotaId]);

            if (!empty($serviciosSync)) {
                $pastAppointment->servicios()->sync($serviciosSync);
            }

            if (!empty($productosSync)) {
                $pastAppointment->productos()->sync($productosSync);
            }

            echo "\n✓ Cita historica creada:\n";
            echo "  Fecha: {$data['fecha']}\n";
            echo "  Hora: {$data['hora']}\n";
            echo "  Estado: {$data['estado']}\n";
        }

        echo "\n" . str_repeat("=", 50) . "\n";
        echo "DATOS CREADOS EXITOSAMENTE!\n";
        echo str_repeat("=", 50) . "\n";
    }
}
