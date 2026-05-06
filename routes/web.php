<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;

Route::get('/storage/{path}', function (string $path) {
    $file = storage_path('app/public/' . $path);
    abort_unless(file_exists($file), 404);
    return response()->file($file);
})->where('path', '.*');


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', [WebController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Shop (listado + filtros)
|--------------------------------------------------------------------------
*/
Route::get('/shop', [WebController::class, 'shop'])
    ->name('shop');

Route::get('/producto/{slug}', [WebController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| Carrito (API interna)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/directo',             [CheckoutController::class, 'directo'])->name('directo');
    Route::get('/confirmar',            [CheckoutController::class, 'confirmar'])->name('confirmar');
    Route::get('/datos',                [CheckoutController::class, 'datos'])->name('datos');
    Route::post('/datos',               [CheckoutController::class, 'datosGuardar'])->name('datos.guardar');
    Route::get('/pago',                 [CheckoutController::class, 'pago'])->name('pago');
    Route::post('/pago',                [CheckoutController::class, 'pagoPost'])->name('pago.post');
    Route::get('/transferencia/{id}',   [CheckoutController::class, 'transferencia'])->name('transferencia');
    Route::post('/transferencia/{id}',  [CheckoutController::class, 'transferenciaPost'])->name('transferencia.post');
    Route::get('/gracias/{id}',         [CheckoutController::class, 'gracias'])->name('gracias');
});

Route::prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/',            [CarritoController::class, 'index'])->name('index');
    Route::post('/agregar',    [CarritoController::class, 'agregar'])->name('agregar');
    Route::post('/quitar',     [CarritoController::class, 'quitar'])->name('quitar');
    Route::post('/actualizar', [CarritoController::class, 'actualizar'])->name('actualizar');
    Route::post('/vaciar',     [CarritoController::class, 'vaciar'])->name('vaciar');
});
