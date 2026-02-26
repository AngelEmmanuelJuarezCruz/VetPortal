<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Veterinaria;
use App\Models\Veterinario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el usuario y veterinaria
        $user = User::where('email', 'test@example.com')->first();
        if (!$user) {
            $this->command->error('No user found with email test@example.com');
            return;
        }

        // Obtener o crear veterinaria
        $veterinaria = Veterinaria::latest('created_at')->first();
        if (!$veterinaria) {
            $veterinaria = Veterinaria::create([
                'nombre' => 'Veterinaria Central',
                'direccion' => 'Calle Principal 123',
                'telefono' => '912345670',
                'correo' => 'info@veterinaria.com',
            ]);
        }

        // Asignar veterinaria al usuario si no la tiene
        if (!$user->tenant_id) {
            $user->update(['tenant_id' => $veterinaria->id]);
        }

        // Obtener o crear cliente
        $cliente = Cliente::where('veterinaria_id', $veterinaria->id)->first();
        if (!$cliente) {
            $cliente = Cliente::create([
                'veterinaria_id' => $veterinaria->id,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'telefono' => '912345678',
                'correo' => 'juan@example.com',
            ]);
        }

        // Obtener o crear mascota
        $mascota = Mascota::where('cliente_id', $cliente->id)->first();
        if (!$mascota) {
            $mascota = Mascota::create([
                'cliente_id' => $cliente->id,
                'veterinaria_id' => $veterinaria->id,
                'nombre' => 'Max',
                'especie' => 'Perro',
                'raza' => 'Golden Retriever',
                'color' => 'Dorado',
                'edad' => 3,
            ]);
        }

        // Obtener o crear veterinario
        $veterinario = Veterinario::where('veterinaria_id', $veterinaria->id)->first();
        if (!$veterinario) {
            $veterinario = Veterinario::create([
                'veterinaria_id' => $veterinaria->id,
                'nombre' => 'Dr. García',
                'apellido' => 'López',
                'numero_cedula' => '123456789',
                'especialidad' => 'Cirugía',
                'telefono' => '912345679',
                'correo' => 'garcia@example.com',
            ]);
        }

        $motivos = ['Revisión general', 'Vacunación', 'Desparasitación', 'Limpieza dental', 'Cirugía menor', 'Extracción de diente', 'Radiografía', 'Suturas', 'Chequeo anual', 'Inyectable'];
        $horas = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $especies = ['Perro', 'Gato', 'Ave', 'Conejo', 'Hamster', 'Tortuga'];
        $razas = ['Mestizo', 'Labrador', 'Siamés', 'Bulldog', 'Persa', 'Poodle', 'Pastor Alemán', 'Beagle'];
        $estados = ['pendiente', 'confirmada', 'cancelada', 'finalizada'];
        $faker = fake('es_ES');

        // Crear múltiples clientes y mascotas para más variedad
        $clientes_array = [];
        $mascotas_array = [];
        
        for ($c = 0; $c < 10; $c++) {
            $cli = Cliente::firstOrCreate(
                ['correo' => "cliente$c@example.com", 'veterinaria_id' => $veterinaria->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'nombre' => $faker->firstName(),
                    'apellido' => $faker->lastName(),
                    'telefono' => $faker->unique()->numerify('9########'),
                ]
            );
            $clientes_array[] = $cli->id;

            // Crear 2 mascotas por cliente
            for ($m = 0; $m < 2; $m++) {
                $mas = Mascota::firstOrCreate(
                    ['nombre' => "Mascota_{$cli->id}_{$m}", 'cliente_id' => $cli->id],
                    [
                        'veterinaria_id' => $veterinaria->id,
                        'especie' => $faker->randomElement($especies),
                        'raza' => $faker->randomElement($razas),
                        'descripcion' => $faker->sentence(5),
                        'fecha_nacimiento' => $faker->dateTimeBetween('-15 years', '-1 year')->format('Y-m-d'),
                    ]
                );
                $mascotas_array[] = $mas->id;
            }
        }

        // Crear múltiples veterinarios
        $veterinarios_array = [];
        for ($v = 0; $v < 5; $v++) {
            $vet = Veterinario::firstOrCreate(
                ['correo' => "veterinario$v@example.com", 'veterinaria_id' => $veterinaria->id],
                [
                    'nombre' => $faker->firstName() . ' ' . $faker->lastName(),
                    'especialidad' => $faker->randomElement(['Cirugía', 'Dermatología', 'Oncología', 'Oftalmología', 'Cardiología']),
                    'telefono' => $faker->unique()->numerify('9########'),
                ]
            );
            $veterinarios_array[] = $vet->id;
        }

        // Generar 50 citas distribuidas en 30 días
        for ($i = 0; $i < 50; $i++) {
            $dias_offset = rand(-5, 30);
            $fecha = today()->addDays($dias_offset);
            
            Cita::create([
                'veterinaria_id' => $veterinaria->id,
                'cliente_id' => $clientes_array[array_rand($clientes_array)],
                'mascota_id' => $mascotas_array[array_rand($mascotas_array)],
                'veterinario_id' => $veterinarios_array[array_rand($veterinarios_array)],
                'user_id' => $user->id,
                'fecha' => $fecha,
                'hora' => $horas[array_rand($horas)],
                'motivo' => $motivos[array_rand($motivos)],
                'estado' => $estados[array_rand($estados)],
            ]);
        }

        $this->command->info('50 citas creadas exitosamente con datos válidos.');
    }
}
