<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permisosPorModulo = [
            'Usuario' => [
                'Usuario Listar',
                'Usuario Crear',
                'Usuario Editar',
                'Usuario Eliminar',
            ],
            'Rol' => [
                'Rol Listar',
                'Rol Crear',
                'Rol Editar',
                'Rol Eliminar',
            ],
            'TipoHabitacion' => [
                'TipoHabitacion Listar',
                'TipoHabitacion Crear',
                'TipoHabitacion Editar',
                'TipoHabitacion Eliminar',
            ],
            'Habitacion' => [
                'Habitacion Listar',
                'Habitacion Crear',
                'Habitacion Editar',
                'Habitacion Eliminar',
            ],
            'Cliente' => [
                'Cliente Listar',
                'Cliente Crear',
                'Cliente Editar',
                'Cliente Eliminar',
            ],
            'Asignacion' => [
                'Asignacion Listar',
                'Asignacion Crear',
                'Asignacion Editar',
                'Asignacion Detalle',
                'Asignacion Eliminar',
            ],
            'Proveedor' => [
                'Proveedor Listar',
                'Proveedor Crear',
                'Proveedor Editar',
                'Proveedor Eliminar',
            ],
            'Producto' => [
                'Producto Listar',
                'Producto Crear',
                'Producto Editar',
                'Producto Eliminar',
            ],
            'Stock' => [
                'Stock Listar',
                'Stock Ajustar',
            ],
            'Compra' => [
                'Compra Listar',
                'Compra Crear',
                'Compra Detalle',
                'Compra Eliminar',
            ],
            'Venta' => [
                'Venta Listar',
                'Venta Crear',
                'Venta Detalle',
                'Venta Eliminar',
            ],
            'Pago' => [
                'Pago Listar',
                'Pago Crear',
                'Pago Eliminar',
            ],
            'Reporte' => [
                'Reporte Hospedaje',
                'Reporte Ventas',
                'Reporte Compras',
            ],
        ];

        foreach ($permisosPorModulo as $modulo => $permisos) {
            foreach ($permisos as $nombrePermiso) {
                Permission::firstOrCreate([
                    'name' => $nombrePermiso,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Asignar todos los permisos al rol superadmin y admin
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        
        $todosPermisos = Permission::all();
        $superadmin->syncPermissions($todosPermisos);
        $admin->syncPermissions($todosPermisos);
    }
}
