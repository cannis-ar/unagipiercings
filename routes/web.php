<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

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
