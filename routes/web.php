<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\DashboardController;

/*
|-------------------------------------------------------------at-------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function(){
    return redirect('/login');
});


Route::middleware([])->controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout');
});

Route::middleware('auth')->group(function(){

    Route::prefix('image')->controller(DropzoneController::class)->group(function(){
        Route::post('/upload/{dirname}','uploadImage')->name('uploadImage');
        Route::post('/remove/{dirname}','removeImage')->name('removeImage');
    });

    Route::middleware('UserPermission:Admin')->prefix('admin')->group(function(){
        Route::post('/upload/description/image', [ProductController::class, 'uploadDescriptionImage']);
        Route::controller(DashboardController::class)->group(function(){
            Route::get('/dashboard', 'index');
        });

        Route::resource('products', ProductController::class);
    });


});

