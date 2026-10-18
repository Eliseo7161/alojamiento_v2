<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoHabitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            [
                'nombre_estado' => 'DISPONIBLE',
                'descripcion'   => 'Habitación lista y limpia para asignar',
                'color_hex'     => '#28a745', // verde
            ],
            [
                'nombre_estado' => 'OCUPADA',
                'descripcion'   => 'Habitación actualmente con huéspedes',
                'color_hex'     => '#dc3545', // rojo
            ],
            [
                'nombre_estado' => 'EN LIMPIEZA',
                'descripcion'   => 'Habitación en proceso de aseo y desinfección',
                'color_hex'     => '#ffc107', // amarillo/naranja
            ],
            [
                'nombre_estado' => 'MANTENIMIENTO',
                'descripcion'   => 'Habitación fuera de servicio por reparaciones',
                'color_hex'     => '#6c757d', // gris
            ],
            [
                'nombre_estado' => 'RESERVADA',
                'descripcion'   => 'Habitación reservada con anticipación',
                'color_hex'     => '#17a2b8', // celeste
            ],
        ];

        foreach ($estados as $estado) {
            DB::table('ESTADO_HABITACION')->updateOrInsert(
                ['nombre_estado' => $estado['nombre_estado']],
                [
                    'descripcion' => $estado['descripcion'],
                    'color_hex'   => $estado['color_hex'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }

        // Datos iniciales de Tipo de Habitación si no existen
        $tipos = [
            ['nombre_tipo' => 'Simple', 'precio_noche' => 80.00, 'capacidad' => 1],
            ['nombre_tipo' => 'Doble', 'precio_noche' => 120.00, 'capacidad' => 2],
            ['nombre_tipo' => 'Matrimonial', 'precio_noche' => 140.00, 'capacidad' => 2],
            ['nombre_tipo' => 'Familiar', 'precio_noche' => 200.00, 'capacidad' => 4],
            ['nombre_tipo' => 'Suite VIP', 'precio_noche' => 260.00, 'capacidad' => 3],
        ];

        foreach ($tipos as $tipo) {
            DB::table('TIPO_HABITACION')->updateOrInsert(
                ['nombre_tipo' => $tipo['nombre_tipo']],
                [
                    'precio_noche' => $tipo['precio_noche'],
                    'capacidad'    => $tipo['capacidad'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
