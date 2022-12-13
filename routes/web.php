<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Auth;
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

// route client
Route::get('/', [HomeController::class, 'index'])->name('client.home');


Route::get('product/{category_id}', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('product-detail/{id}', [ClientProductController::class, 'show'])->name('client.products.show');

Route::middleware('auth')->group(function(){
    Route::post('add-to-cart', [CartController::class, 'store'])->name('client.carts.add');
    Route::get('carts', [CartController::class, 'index'])->name('client.carts.index');
    Route::post('update-quantity-product-in-cart/{cart_product_id}', [CartController::class, 'updateQuantityProduct'])->name('client.carts.update_product_quantity');
    Route::post('remove-product-in-cart/{cart_product_id}', [CartController::class, 'removeProductInCart'])->name('client.carts.remove_product');


    Route::get('checkout', [CartController::class, 'checkout'])->name('client.checkout.index')->middleware('user.can_checkout_cart');
    Route::post('process-checkout', [CartController::class, 'processCheckout'])->name('client.checkout.proccess')->middleware('user.can_checkout_cart');

    Route::get('list-orders', [OrderController::class, 'index'])->name('client.orders.index');

    Route::post('orders/cancel/{id}', [OrderController::class, 'cancel'])->name('client.orders.cancel');

});


Auth::routes();


// route admin
Route::middleware('auth')->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->group(function () {
        Route::prefix('roles')->controller(RoleController::class)->name('roles.')->group(function(){
            Route::get('/', 'index')->name('index')->middleware('role:super-admin');
            Route::post('/', 'store')->name('store')->middleware('role:super-admin');
            Route::get('/create', 'create')->name('create')->middleware('role:super-admin');
            Route::get('/{coupon}', 'show')->name('show')->middleware('role:super-admin');
            Route::put('/{coupon}', 'update')->name('update')->middleware('role:super-admin');
            Route::delete('/{coupon}', 'destroy')->name('destroy')->middleware('role:super-admin');
            Route::get('/{coupon}/edit', 'edit')->name('edit')->middleware('role:super-admin');
        });
    
        Route::prefix('/users')->controller(UserController::class)->name('users.')->group(function(){
            Route::get('/', 'index')->name('index')->middleware('permission:show-user');
            Route::post('/', 'store')->name('store')->middleware('permission:create-user');
            Route::get('/create', 'create')->name('create')->middleware('permission:create-user');
            Route::get('/{id}', 'show')->name('show')->middleware('permission:show-user');
            Route::put('/{id}', 'update')->name('update')->middleware('permission:update-user');
            Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:delete-user');
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:update-user');
        });
    
        Route::prefix('categories')->controller(CategoryController::class)->name('categories.')->group(function(){
            Route::get('/', 'index')->name('index')->middleware('permission:show-category');
            Route::post('/', 'store')->name('store')->middleware('permission:create-category');
            Route::get('/create', 'create')->name('create')->middleware('permission:create-category');
            Route::get('/{id}', 'show')->name('show')->middleware('permission:show-category');
            Route::put('/{id}', 'update')->name('update')->middleware('permission:update-category');
            Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:delete-category');
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:update-category');
        });
    
        Route::prefix('products')->controller(ProductController::class)->name('products.')->group(function(){
            Route::get('/', 'index')->name('index')->middleware('permission:show-product');
            Route::post('/', 'store')->name('store')->middleware('permission:create-product');
            Route::get('/create', 'create')->name('create')->middleware('permission:create-product');
            Route::get('/{id}', 'show')->name('show')->middleware('permission:show-product');
            Route::put('/{id}', 'update')->name('update')->middleware('role:super-admin');
            Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:delete-product');
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:update-product');
        });
    
        Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::post('update-status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    });
});







