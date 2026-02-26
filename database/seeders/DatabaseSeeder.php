<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Veterinaria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $veterinaria = Veterinaria::latest('created_at')->first();
        if (! $veterinaria) {
            return;
        }

        $faker = fake('es_ES');

        for ($i = 0; $i < 10; $i++) {
            $cliente = Cliente::create([
                'uuid' => (string) Str::uuid(),
                'veterinaria_id' => $veterinaria->id,
                'nombre' => $faker->firstName(),
                'apellido' => $faker->lastName(),
                'telefono' => $faker->unique()->numerify('9########'),
                'correo' => $faker->unique()->safeEmail(),
            ]);

            Mascota::create([
                'cliente_id' => $cliente->id,
                'veterinaria_id' => $veterinaria->id,
                'nombre' => $faker->firstName(),
                'especie' => $faker->randomElement(['Perro', 'Gato', 'Ave', 'Conejo']),
                'raza' => $faker->randomElement(['Mestizo', 'Labrador', 'Siames', 'Bulldog', 'Persa']),
                'descripcion' => $faker->sentence(8),
                'fecha_nacimiento' => $faker->dateTimeBetween('-12 years', '-3 months')->format('Y-m-d'),
            ]);
        }

        // Seed productos y servicios
        $this->call([
            ProductoSeeder::class,
            ServicioSeeder::class,
            VeterinarioSeeder::class,
        ]);
    }
}
