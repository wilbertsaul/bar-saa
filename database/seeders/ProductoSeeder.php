<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // precio_venta = Precio Carta (efectivo / yape / plin)
        // precio_tarjeta = Precio con tarjeta de crédito o débito
        $productos = [
            // Ron
            ['nombre' => 'Ron Flor de Caña 7 Años 750ML', 'categoria' => 'ron', 'stock_actual' => 15, 'precio_venta' => 130.00, 'precio_tarjeta' => 137.00],
            ['nombre' => "Botran Ron Añejo 5 Años 750ML", 'categoria' => 'ron', 'stock_actual' => 15, 'precio_venta' => 80.00, 'precio_tarjeta' => 85.00],
            ['nombre' => "Botran Ron Añejo 8 Años 750ML", 'categoria' => 'ron', 'stock_actual' => 15, 'precio_venta' => 120.00, 'precio_tarjeta' => 126.00],

            // Whisky
            ['nombre' => "Jack Daniel's Tennessee Whiskey 750ML", 'categoria' => 'whisky', 'stock_actual' => 15, 'precio_venta' => 160.00, 'precio_tarjeta' => 168.00],
            ['nombre' => 'Chivas Regal Whisky 12 Años 700ML', 'categoria' => 'whisky', 'stock_actual' => 15, 'precio_venta' => 140.00, 'precio_tarjeta' => 147.00],
            ['nombre' => "Johnnie Walker Black Label 12 Años 750ML", 'categoria' => 'whisky', 'stock_actual' => 15, 'precio_venta' => 170.00, 'precio_tarjeta' => 179.00],
            ['nombre' => 'Whisky J. Walker Double Black 750ML', 'categoria' => 'whisky', 'stock_actual' => 20, 'precio_venta' => 210.00, 'precio_tarjeta' => 221.00],
            ['nombre' => 'Whisky Johnnie Walker Gold Label Reserve 750ML', 'categoria' => 'whisky', 'stock_actual' => 10, 'precio_venta' => 350.00, 'precio_tarjeta' => 370.00],

            // Cerveza
            ['nombre' => 'Cusqueña Dorada 310ML', 'categoria' => 'cerveza', 'stock_actual' => 360, 'precio_venta' => 9.00, 'precio_tarjeta' => 10.00],

            // Sin alcohol
            ['nombre' => 'San Mateo Agua Sin Gas 600ML', 'categoria' => 'sin_alcohol', 'stock_actual' => 225, 'precio_venta' => 3.00, 'precio_tarjeta' => 4.00],
            ['nombre' => 'San Mateo Agua Con Gas 600ML', 'categoria' => 'sin_alcohol', 'stock_actual' => 225, 'precio_venta' => 3.00, 'precio_tarjeta' => 4.00],
            ['nombre' => 'Coca Cola 600ML', 'categoria' => 'sin_alcohol', 'stock_actual' => 180, 'precio_venta' => 5.00, 'precio_tarjeta' => 6.00],
            ['nombre' => 'Red Bull Energy Drink 250ML', 'categoria' => 'sin_alcohol', 'stock_actual' => 15, 'precio_venta' => 12.00, 'precio_tarjeta' => 13.00],
        ];

        foreach ($productos as $p) {
            Producto::firstOrCreate(['nombre' => $p['nombre']], $p + [
                'stock_minimo'      => 5,
                'unidades_por_caja' => 1,
                'activo'            => true,
            ]);
        }
    }
}
