<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\Veterinaria;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $veterinarias = Veterinaria::all();

        if ($veterinarias->isEmpty()) {
            return;
        }

        $veterinaria = $veterinarias->first();

        $servicios = [
            // Salud
            [
                'nombre' => 'Consulta General',
                'categoria' => 'salud',
                'descripcion' => 'Consulta veterinaria general de diagnóstico',
                'duracion_estimada' => 30,
                'precio' => 50.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Vacunación - Rabia',
                'categoria' => 'salud',
                'descripcion' => 'Aplicación de vacuna antirrábica',
                'duracion_estimada' => 15,
                'precio' => 45.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Vacunación - DHPP',
                'categoria' => 'salud',
                'descripcion' => 'Aplicación de vacuna combo',
                'duracion_estimada' => 15,
                'precio' => 50.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Desparasitación Interna',
                'categoria' => 'salud',
                'descripcion' => 'Tratamiento de parásitos internos',
                'duracion_estimada' => 20,
                'precio' => 30.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Desparasitación Externa',
                'categoria' => 'salud',
                'descripcion' => 'Tratamiento de pulgas y garrapatas',
                'duracion_estimada' => 20,
                'precio' => 35.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Limpieza Dental',
                'categoria' => 'salud',
                'descripcion' => 'Limpieza dental profesional con anestesia',
                'duracion_estimada' => 60,
                'precio' => 120.00,
                'activo' => true,
            ],

            // Estética
            [
                'nombre' => 'Baño Completo',
                'categoria' => 'estetica',
                'descripcion' => 'Baño con secado y peinado',
                'duracion_estimada' => 60,
                'precio' => 40.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte de Pelo',
                'categoria' => 'estetica',
                'descripcion' => 'Corte de pelo a máquina o tijera según raza',
                'duracion_estimada' => 90,
                'precio' => 60.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte de Uñas',
                'categoria' => 'estetica',
                'descripcion' => 'Corte y limpieza de uñas',
                'duracion_estimada' => 20,
                'precio' => 15.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Limpieza de Oídos',
                'categoria' => 'estetica',
                'descripcion' => 'Limpieza profunda de conducto auditivo',
                'duracion_estimada' => 30,
                'precio' => 25.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Expresión de Glándulas Anales',
                'categoria' => 'estetica',
                'descripcion' => 'Vaciado manual de glándulas anales',
                'duracion_estimada' => 15,
                'precio' => 20.00,
                'activo' => true,
            ],

            // Cirugía
            [
                'nombre' => 'Esterilización Hembra',
                'categoria' => 'cirugia',
                'descripcion' => 'Cirugía de ovariohisterectomía',
                'duracion_estimada' => 120,
                'precio' => 250.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Castración Macho',
                'categoria' => 'cirugia',
                'descripcion' => 'Castración quirúrgica',
                'duracion_estimada' => 90,
                'precio' => 180.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Extracción Dental',
                'categoria' => 'cirugia',
                'descripcion' => 'Extracción de dientes dañados',
                'duracion_estimada' => 60,
                'precio' => 150.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Reparación de Heridas',
                'categoria' => 'cirugia',
                'descripcion' => 'Sutura y tratamiento de heridas',
                'duracion_estimada' => 45,
                'precio' => 100.00,
                'activo' => true,
            ],

            // Bienestar
            [
                'nombre' => 'Control de Peso',
                'categoria' => 'bienestar',
                'descripcion' => 'Evaluación y plan de control de peso',
                'duracion_estimada' => 30,
                'precio' => 40.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Nutrición Personalizada',
                'categoria' => 'bienestar',
                'descripcion' => 'Consulta nutricional y recomendaciones dietéticas',
                'duracion_estimada' => 45,
                'precio' => 60.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Terapia Física',
                'categoria' => 'bienestar',
                'descripcion' => 'Sesión de fisioterapia y rehabilitación',
                'duracion_estimada' => 50,
                'precio' => 70.00,
                'activo' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create([
                ...$servicio,
                'veterinaria_id' => $veterinaria->id,
                'tenant_id' => $veterinaria->id,
            ]);
        }
    }
}
