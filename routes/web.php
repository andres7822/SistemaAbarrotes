<?php

use App\Http\Controllers\bodegaController;
use App\Http\Controllers\cajaController;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\inventarioController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\presentacioneController;
use App\Http\Controllers\productoController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\subcategoriaController;
use App\Http\Controllers\tiendaController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ventaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);

Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [homeController::class, 'index'])->name('home');
//-----------------PDFs--------------------------
    Route::get('inventario/reporte', [inventarioController::class, 'reporte'])->name('inventario.reporte');
    Route::get('venta/imprimir_ticket', [ventaController::class, 'imprimirTicket'])->name('venta.imprimir_ticket');
//-----------------------------------------------
    Route::resources([
        'menu' => menuController::class,
        'categoria' => categoriaController::class,
        'subcategoria' => subcategoriaController::class,
        'producto' => productoController::class,
        'inventario' => inventarioController::class,
        'cliente' => clienteController::class,
        'user' => userController::class,
        'role' => roleController::class,
        'marca' => marcaController::class,
        'presentacione' => presentacioneController::class,
        'venta' => ventaController::class,
        'caja' => cajaController::class,
        'tienda' => tiendaController::class,
        'bodega' => bodegaController::class
    ], [
        'parameters' => [
            'categoria' => 'categoria',
            'subcategoria' => 'subcategoria',
            'venta' => 'venta'
        ]
    ]);
});

//-----------------AJAXS-------------------------
Route::post('subcategoria/subcategoria_by_cat', [subcategoriaController::class, 'subcategoriaByCat'])->name('subcategoria.subcategoria_by_cat');
Route::post('venta/cobrar_venta', [ventaController::class, 'cobrarVenta'])->name('venta.cobrar_venta');
Route::post('venta/actualizar_cliente', [ventaController::class, 'actualizarCliente'])->name('venta.actualizar_cliente');
Route::post('inventario/listado_select', [inventarioController::class, 'listadoSelect'])->name('inventario.listado_select');
Route::post('inventario/actualizar_inventario', [inventarioController::class, 'actualizarInventario'])->name('inventario.actualizar_inventario');
//-----------------------------------------------

