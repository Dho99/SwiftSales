<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ForgotPasswordController;



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


Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');


Route::get('/', function(){
    return redirect('/login');
});



Route::middleware([])->controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout');
});

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'storeRegisteredAccount'])->name('registered');


Route::middleware('auth')->group(function(){
    Route::resource('user', UserController::class);

    Route::prefix('image')->controller(DropzoneController::class)->group(function(){
        Route::post('/upload/{dirname}','uploadImage')->name('uploadImage');
        Route::post('/remove/{dirname}','removeImage')->name('removeImage');
    });

    Route::controller(ProductController::class)->group(function(){
        Route::get('/products/{skip}/get', 'ajaxRequestProduct');
        Route::get('/products/stock/in', 'stockInView')->name('stockinProduct');
        Route::get('/products/stock/in/{code}', 'ajaxGetProduct');
        Route::post('/products/stock/in/store', 'storeNewStock');
    });


    Route::resource('products', ProductController::class);


    Route::resource('transactions', TransactionController::class);
    // Route::get('/transactions/{month}/{year}', [TransactionController::class, 'filterByMonth']);

    Route::get('/transactions/print/{transaction}', [TransactionController::class, 'print']);


    Route::prefix('history')->group(function(){
        Route::get('/stock/in', [StockHistoryController::class, 'index']);
    });



    Route::middleware('UserPermission:Admin')->prefix('admin')->group(function(){
        Route::resource('supplier', SupplierController::class);

        Route::prefix('customer')->controller(UserController::class)->group(function(){
            Route::get('/list', 'customerLists')->name('customerLists');
            Route::put('/{user}', 'updateCustomer')->name('customerUpdate');
        });

        Route::controller(DashboardController::class)->group(function(){
            Route::get('/dashboard', 'index');
            Route::get('/render/chart', 'renderChart');
        });


    });

    // Route::middleware('UserPermission:Cashier')->prefix('customer')->group(function(){

    // });

});

