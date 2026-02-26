<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'ac5892496@gmail.com')->whereNotNull('tenant_id')->first();

        if (!$user) {
            $user = User::whereNotNull('tenant_id')->first();
        }

        if (!$user) {
            echo "Error: No hay usuario con tenant\n";
            return;
        }

        $veterinariaId = $user->tenant_id;
        $tenantId = $user->tenant_id;

        $productsByType = [
            'medicamento' => [
                'Vacunas' => [
                    'Vacuna Trivalente',
                    'Vacuna Antirrabica',
                    'Vacuna Polivalente',
                    'Vacuna Felina',
                    'Vacuna Bordetella',
                ],
                'Antiparasitarios' => [
                    'Antiparasitario Premium',
                    'Pipeta Antipulgas',
                    'Collar Antiparasitario',
                    'Tableta Antipulgas',
                    'Spray Antiparasitario',
                ],
                'Antibióticos' => [
                    'Antibiotico VetCare',
                    'Antibiotico Canino',
                    'Antibiotico Felino',
                    'Antibiotico Inyectable',
                    'Antibiotico Topico',
                ],
                'Material Médico' => [
                    'Jeringas Estériles',
                    'Guantes Quirúrgicos',
                    'Gasas Estériles',
                    'Vendas Elasticas',
                    'Solucion Antiseptica',
                ],
            ],
            'articulo' => [
                'Accesorios' => [
                    'Collar Ajustable',
                    'Correa Nylon',
                    'Placa Identificacion',
                    'Arnes Ajustable',
                    'Transportadora Mediana',
                ],
                'Higiene' => [
                    'Shampoo Dermatologico',
                    'Cepillo Doble',
                    'Corta Uñas',
                    'Toallitas Higienicas',
                    'Kit Limpieza Oidos',
                ],
            ],
            'alimento' => [
                'Alimentos' => [
                    'Croquetas Adulto',
                    'Croquetas Cachorro',
                    'Alimento Humedo',
                    'Snack Dental',
                    'Dieta Especial',
                ],
            ],
        ];

        echo "\nProductos creados o actualizados:\n";
        foreach ($productsByType as $tipo => $categories) {
            foreach ($categories as $categoria => $names) {
                foreach ($names as $index => $name) {
                    $producto = Producto::updateOrCreate(
                        [
                            'veterinaria_id' => $veterinariaId,
                            'tenant_id' => $tenantId,
                            'tipo' => $tipo,
                            'nombre' => $name,
                        ],
                        [
                            'uuid' => (string) Str::uuid(),
                            'categoria' => $categoria,
                            'descripcion' => 'Producto de prueba para reportes',
                            'stock_actual' => 20 + ($index * 3),
                            'stock_minimo' => 5,
                            'precio_compra' => 45 + ($index * 10),
                            'precio_venta' => 80 + ($index * 12),
                            'proveedor' => 'Proveedor Demo',
                            'fecha_caducidad' => in_array($tipo, ['medicamento', 'alimento'], true) ? now()->addMonths(12) : null,
                        ]
                    );

                    echo "  - {$producto->nombre} ({$producto->categoria})\n";
                }
            }
        }

        $servicesByCategory = [
            'salud' => [
                'Consulta General',
                'Vacunacion',
                'Desparasitacion',
            ],
            'estetica' => [
                'Bano y Corte',
                'Limpieza de Oidos',
                'Corte de Uñas',
            ],
            'cirugia' => [
                'Esterilizacion',
                'Extraccion',
                'Cirugia Menor',
            ],
            'bienestar' => [
                'Evaluacion Nutricional',
                'Terapia de Rehabilitacion',
                'Chequeo Preventivo',
            ],
        ];

        echo "\nServicios creados o actualizados:\n";
        foreach ($servicesByCategory as $categoria => $names) {
            foreach ($names as $index => $name) {
                $servicio = Servicio::firstOrCreate(
                    [
                        'veterinaria_id' => $veterinariaId,
                        'tenant_id' => $tenantId,
                        'categoria' => $categoria,
                        'nombre' => $name,
                    ],
                    [
                        'descripcion' => 'Servicio de prueba para reportes',
                        'duracion_estimada' => 30 + ($index * 10),
                        'precio' => 250 + ($index * 50),
                        'activo' => true,
                    ]
                );

                echo "  - {$servicio->nombre} ({$servicio->categoria})\n";
            }
        }

        echo "\nCatalogo de prueba listo.\n";
    }
}
