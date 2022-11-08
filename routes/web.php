<?php

use App\Http\Controllers\DestroyOrderController;
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

Route::get('/', function () {
    return view('main');
});

Route::middleware(['auth'])->group(function(){
    Route::get('admin/orders/delete/{order}', DestroyOrderController::class)->name('order.destroy');
});
