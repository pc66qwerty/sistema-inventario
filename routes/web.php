<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ClienteController; // ✅ Agregado

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // 📊 Dashboard general (todos los roles pueden entrar)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 🧾 Módulo de ventas (solo vendedor y jefe)
    Route::middleware('modulo:ventas')->group(function () {
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');

        // Ruta para autocompletado de clientes
        Route::get('/ventas/buscar-clientes', [VentaController::class, 'buscarClientes'])
            ->name('ventas.buscar-clientes');

        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');

        // Mostrar detalles de una venta (para modal)
        Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    });

    // 👤 Módulo de clientes (asociado a inventario)
    Route::middleware('modulo:inventario')->group(function () {
        Route::resource('clientes', ClienteController::class);

        // 📦 Módulo de inventario (inventario y jefe)
        Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/inventario/create', [InventarioController::class, 'create'])->name('inventario.create');
        Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
        Route::get('/inventario/{producto}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
        Route::put('/inventario/{producto}', [InventarioController::class, 'update'])->name('inventario.update');
        Route::delete('/inventario/{producto}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
    });

    // 📈 Módulo de reportes (accesible para vendedores y jefes)
    Route::middleware('modulo:ventas')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.pdf');
    });

    // 👥 Módulo de usuarios (solo jefe)
    Route::middleware('modulo:usuarios')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        Route::post('/usuarios/{usuario}/logout-everywhere', [UsuarioController::class, 'logoutEverywhere'])
            ->name('usuarios.logout-everywhere');
        Route::post('/usuarios/password/reset-link', [UsuarioController::class, 'sendResetLink'])
            ->name('usuarios.password.email');
    });

});
