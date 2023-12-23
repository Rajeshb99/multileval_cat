<?php

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

use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'index']); // Specify the method for the index action
Route::post('/categories/store', [CategoryController::class, 'store'])->name("category.store");
Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name("category.update");
Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name("category.destroy");


