<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\productoController;
use App\Http\Controllers\subcategoriaController;
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
    Route::resources([
        'menu' => menuController::class,
        'categoria' => categoriaController::class,
        'subcategoria' => subcategoriaController::class,
        'producto' => productoController::class,
        'cliente' => clienteController::class
    ]);
});
