<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Veterinaria;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $veterinarias = Veterinaria::all();

        if ($veterinarias->isEmpty()) {
            return;
        }

        $veterinaria = $veterinarias->first();

        $productos = [
            // Medicamentos
            [
                'nombre' => 'Vacuna Rabia',
                'tipo' => 'medicamento',
                'categoria' => 'Vacunas',
                'descripcion' => 'Vacuna contra rabia canina',
                'stock_actual' => 50,
                'stock_minimo' => 10,
                'precio_compra' => 15.00,
                'precio_venta' => 35.00,
                'proveedor' => 'Boehringer Ingelheim',
            ],
            [
                'nombre' => 'Vacuna DHPP',
                'tipo' => 'medicamento',
                'categoria' => 'Vacunas',
                'descripcion' => 'Vacuna combo (Distemper, Hepatitis, Parvovirosis, Parainfluenza)',
                'stock_actual' => 45,
                'stock_minimo' => 10,
                'precio_compra' => 18.00,
                'precio_venta' => 40.00,
                'proveedor' => 'Pfizer',
            ],
            [
                'nombre' => 'Antiparasitario Interno',
                'tipo' => 'medicamento',
                'categoria' => 'Antiparasitarios',
                'descripcion' => 'Tabletas para desparasitación interna',
                'stock_actual' => 100,
                'stock_minimo' => 20,
                'precio_compra' => 5.00,
                'precio_venta' => 12.00,
                'proveedor' => 'Merck',
            ],
            [
                'nombre' => 'Antiparasitario Externo',
                'tipo' => 'medicamento',
                'categoria' => 'Antiparasitarios',
                'descripcion' => 'Pipeta para pulgas y garrapatas',
                'stock_actual' => 80,
                'stock_minimo' => 15,
                'precio_compra' => 8.00,
                'precio_venta' => 20.00,
                'proveedor' => 'Bayer',
            ],
            [
                'nombre' => 'Antibiótico Amoxicilina',
                'tipo' => 'medicamento',
                'categoria' => 'Antibióticos',
                'descripcion' => 'Amoxicilina 500mg (caja 20 tabletas)',
                'stock_actual' => 30,
                'stock_minimo' => 5,
                'precio_compra' => 12.00,
                'precio_venta' => 25.00,
                'proveedor' => 'Veterinary Plus',
            ],

            // Artículos
            [
                'nombre' => 'Jeringa 3ml',
                'tipo' => 'articulo',
                'categoria' => 'Material Médico',
                'descripcion' => 'Jeringa desechable 3ml con aguja',
                'stock_actual' => 500,
                'stock_minimo' => 100,
                'precio_compra' => 0.30,
                'precio_venta' => 1.00,
                'proveedor' => 'Terumo',
            ],
            [
                'nombre' => 'Algodón Estéril',
                'tipo' => 'articulo',
                'categoria' => 'Material Médico',
                'descripcion' => 'Bolsa de algodón estéril 100g',
                'stock_actual' => 50,
                'stock_minimo' => 10,
                'precio_compra' => 2.00,
                'precio_venta' => 5.00,
                'proveedor' => 'MedSupply',
            ],
            [
                'nombre' => 'Collar Isabelino',
                'tipo' => 'articulo',
                'categoria' => 'Accesorios',
                'descripcion' => 'Collar de recuperación para perros (L)',
                'stock_actual' => 25,
                'stock_minimo' => 5,
                'precio_compra' => 8.00,
                'precio_venta' => 18.00,
                'proveedor' => 'PetCare',
            ],
            [
                'nombre' => 'Correa Veterinaria',
                'tipo' => 'articulo',
                'categoria' => 'Accesorios',
                'descripcion' => 'Correa de nylon ajustable para contención',
                'stock_actual' => 40,
                'stock_minimo' => 10,
                'precio_compra' => 5.00,
                'precio_venta' => 12.00,
                'proveedor' => 'PetCare',
            ],
            [
                'nombre' => 'Shampoo Medicado',
                'tipo' => 'articulo',
                'categoria' => 'Higiene',
                'descripcion' => 'Shampoo para problemas de piel (500ml)',
                'stock_actual' => 35,
                'stock_minimo' => 8,
                'precio_compra' => 10.00,
                'precio_venta' => 22.00,
                'proveedor' => 'Virbac',
            ],

            // Alimentos
            [
                'nombre' => 'Alimento Dieta Renal',
                'tipo' => 'alimento',
                'categoria' => 'Alimentos Veterinarios',
                'descripcion' => 'Comida especializada para insuficiencia renal (10kg)',
                'stock_actual' => 20,
                'stock_minimo' => 3,
                'precio_compra' => 45.00,
                'precio_venta' => 85.00,
                'proveedor' => 'Royal Canin',
                'fecha_caducidad' => now()->addDays(180),
            ],
            [
                'nombre' => 'Alimento Dieta Digestiva',
                'tipo' => 'alimento',
                'categoria' => 'Alimentos Veterinarios',
                'descripcion' => 'Comida para sensibilidad digestiva (10kg)',
                'stock_actual' => 25,
                'stock_minimo' => 3,
                'precio_compra' => 40.00,
                'precio_venta' => 75.00,
                'proveedor' => 'Hill\'s',
                'fecha_caducidad' => now()->addDays(200),
            ],
            [
                'nombre' => 'Alimento Dieta Articular',
                'tipo' => 'alimento',
                'categoria' => 'Alimentos Veterinarios',
                'descripcion' => 'Comida para articulaciones y movilidad (10kg)',
                'stock_actual' => 18,
                'stock_minimo' => 3,
                'precio_compra' => 50.00,
                'precio_venta' => 90.00,
                'proveedor' => 'Royal Canin',
                'fecha_caducidad' => now()->addDays(190),
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create([
                ...$producto,
                'uuid' => \Illuminate\Support\Str::uuid(),
                'veterinaria_id' => $veterinaria->id,
                'tenant_id' => $veterinaria->id,
            ]);
        }
    }
}
