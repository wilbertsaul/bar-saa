<?php

use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\CajaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Autenticación (Breeze) ────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Rutas protegidas (login requerido) ───────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── POS ─────────────────────────────────────────────────────────────
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/venta', [PosController::class, 'registrarVenta'])->name('venta.store');
        Route::post('/venta/api', [PosController::class, 'registrarVentaApi'])->name('venta.storeApi');
        Route::patch('/venta/{venta}/anular', [PosController::class, 'anularVenta'])->name('venta.anular');
    });

    // ── Admin: secciones acceso para Admin y Operador ────────────────
    Route::middleware(['admin.o.control'])->prefix('admin')->name('admin.')->group(function () {

        // Productos
        Route::resource('productos', ProductoController::class)
            ->except(['show']);
        Route::post('/productos/{producto}/stock', [ProductoController::class, 'ingresarStock'])
            ->name('productos.stock');

        // Inventario
        Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');

        // Cajas (turnos)
        Route::get('/cajas', [CajaController::class, 'index'])->name('cajas.index');
        Route::get('/cajas/abrir', [CajaController::class, 'abrir'])->name('cajas.abrir');
        Route::post('/cajas', [CajaController::class, 'store'])->name('cajas.store');
        Route::get('/cajas/{caja}/cerrar', [CajaController::class, 'cerrar'])->name('cajas.cerrar');
        Route::post('/cajas/{caja}/cerrar', [CajaController::class, 'cerrarStore'])->name('cajas.cerrar.store');
        Route::post('/cajas/gasto', [CajaController::class, 'agregarGasto'])->name('cajas.gasto');
        Route::get('/cajas/{caja}/merma', [CajaController::class, 'verMerma'])->name('cajas.merma');
        Route::post('/cajas/merma', [CajaController::class, 'agregarMerma'])->name('cajas.merma.store');
        Route::get('/cajas/{caja}', [CajaController::class, 'show'])->name('cajas.show');
        Route::delete('/cajas/{caja}', [CajaController::class, 'destroy'])->name('cajas.destroy');
    });

    // ── Admin: solo administradores ────────────────────────────────
    Route::middleware(['solo.admin'])->prefix('admin')->name('admin.')->group(function () {

        // Reportes
        Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');

        // Auditoría
        Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    });
});
