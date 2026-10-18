<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AsignacionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de inicio (Login)
Route::get('/', function () {
    return view('auth.login');
});

// Rutas protegidas por Autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MÓDULO GESTIÓN DE HABITACIONES
    Route::resource('tipos-habitacion', TipoHabitacionController::class);
    Route::resource('habitaciones', HabitacionController::class);
    Route::patch('habitaciones/{habitacione}/cambiar-estado', [HabitacionController::class, 'cambiarEstado'])->name('habitaciones.cambiar-estado');

    // MÓDULO RECEPCIÓN Y HOSPEDAJE (Clientes y Asignaciones)
    Route::resource('clientes', ClienteController::class);
    Route::resource('asignaciones', AsignacionController::class);
    Route::patch('asignaciones/{id}/finalizar', [AsignacionController::class, 'finalizar'])->name('asignaciones.finalizar');

    // MÓDULO COMPRAS E INVENTARIO
    Route::resource('proveedores', \App\Http\Controllers\ProveedorController::class);
    Route::post('categorias/rapida', [\App\Http\Controllers\ProductoController::class, 'storeCategoria'])->name('categorias.rapida');
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);
    Route::resource('stock', \App\Http\Controllers\StockController::class)->only(['index', 'update']);
    Route::resource('compras', \App\Http\Controllers\CompraController::class);

    // MÓDULO VENTAS Y CAJA
    Route::resource('ventas', \App\Http\Controllers\VentaController::class);
    Route::resource('pagos', \App\Http\Controllers\PagoController::class)->only(['index', 'store', 'destroy']);

    // RUTAS DE ADMINISTRACIÓN (Exclusivas para Administrador)
    Route::middleware(['can:es-admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('roles', RolController::class);
        Route::resource('permisos', PermisoController::class);
    });

});

require __DIR__ . '/auth.php';