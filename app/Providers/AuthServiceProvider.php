<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // 1. Superadmin y Administrador tienen acceso total a cualquier habilidad/permiso
        Gate::before(function ($user, $ability) {
            $rolDirecto = strtolower($user->role ?? $user->rol ?? '');
            if (in_array($rolDirecto, ['admin', 'administrador', 'superadmin'])) {
                return true;
            }
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('admin') || $user->hasRole('administrador') || $user->hasRole('superadmin')) {
                    return true;
                }
            }
            return null; // Deja que Spatie evalúe el permiso si no es admin
        });

        // 2. Gate 'es-admin': verdadero para admin o si el rol tiene permisos de administración
        Gate::define('es-admin', function ($user) {
            $rolDirecto = strtolower($user->role ?? $user->rol ?? '');
            if (in_array($rolDirecto, ['admin', 'administrador', 'superadmin'])) {
                return true;
            }
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('admin') || $user->hasRole('administrador') || $user->hasRole('superadmin')) {
                    return true;
                }
            }
            return $user->can('Usuario Listar') || $user->can('Rol Listar');
        });

        // 3. Gates para encabezados de módulos en el menú
        Gate::define('ver-modulo-administracion', function ($user) {
            return $user->can('Usuario Listar') || $user->can('Rol Listar') || $user->can('es-admin');
        });

        Gate::define('ver-modulo-compras', function ($user) {
            return $user->can('Proveedor Listar') 
                || $user->can('Producto Listar') 
                || $user->can('Stock Listar') 
                || $user->can('Compra Listar') 
                || $user->can('es-admin');
        });

        Gate::define('ver-modulo-reportes', function ($user) {
            return $user->can('Reporte Hospedaje') 
                || $user->can('Reporte Ventas') 
                || $user->can('Reporte Compras') 
                || $user->can('es-admin');
        });
    }
}