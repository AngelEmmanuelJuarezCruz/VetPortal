<?php

namespace Database\Seeders;

use App\Models\Veterinaria;
use App\Models\Veterinario;
use Illuminate\Database\Seeder;

class VeterinarioSeeder extends Seeder
{
    public function run(): void
    {
        $veterinaria = Veterinaria::latest('created_at')->first();

        if (! $veterinaria) {
            return;
        }

        $faker = fake('es_ES');
        $especialidades = [
            'Medicina general',
            'Cirugia',
            'Dermatologia',
            'Odontologia',
            'Traumatologia',
            'Cardiologia',
            'Oftalmologia',
            'Exoticos',
            'Reproduccion',
            'Diagnostico por imagen',
        ];

        for ($i = 0; $i < 10; $i++) {
            Veterinario::create([
                'veterinaria_id' => $veterinaria->id,
                'nombre' => $faker->name(),
                'especialidad' => $faker->randomElement($especialidades),
                'telefono' => $faker->unique()->numerify('9########'),
                'correo' => $faker->unique()->safeEmail(),
                'activo' => true,
            ]);
        }
    }
}
