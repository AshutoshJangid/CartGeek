<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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

Route::POST('products/{id}',[ ProductController::class,'Update']);
Route::resource('products', ProductController::class);

// Route::get('/', function () {
//     return view('index');
// });
Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');