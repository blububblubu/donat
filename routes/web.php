<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/product/{product}', [HomeController::class,'show'])->name('product.show');

//search
Route::get('/search', [HomeController::class, 'search'])->name('search');

//produk
Route::get('/products', [HomeController::class,'products'])->name('products.index');

Route::get('/products/{product}', [HomeController::class,'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('register',[RegisterController::class,'show'])->name('register.show');
Route::post('register',[RegisterController::class,'register'])->name('register');

Route::get('login',[LoginController::class,'show'])->name('login.show');
Route::post('login',[LoginController::class,'login'])->name('login');

Route::post('logout',[LoginController::class,'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| BUYER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    // CART
    Route::get('cart',[CartController::class,'index'])->name('cart.index');
    Route::post('cart/add/{product}',[CartController::class,'add'])->name('cart.add');
    Route::delete('cart/remove/{cartItem}',[CartController::class,'remove'])->name('cart.remove');
    Route::put('cart/update/{cartItem}',[CartController::class,'update'])->name('cart.update');
    Route::post('cart/clear',[CartController::class,'clear'])->name('cart.clear');

    // CHECKOUT
    Route::get('cart/checkout',[CartController::class,'checkout'])->name('cart.checkout');
    Route::post('cart/checkout',[CartController::class,'processCheckout'])->name('cart.processCheckout');

    // ORDERS
    Route::post('orders',[OrderController::class,'store'])->name('orders.store');
    Route::get('orders/my',[OrderController::class,'myOrders'])->name('orders.my');

    Route::get('orders/{order}/invoice',[OrderController::class,'invoice'])->name('orders.invoice');
    Route::post('orders/{order}/upload-proof',[OrderController::class,'uploadProof'])->name('orders.uploadProof');

    Route::post('orders/{order}/pay',[OrderController::class,'pay'])->name('orders.pay');

    // REVIEWS
    Route::post('product/{product}/review',[ReviewController::class,'store'])->name('product.review');

    // ADDRESSES
    Route::resource('addresses', AddressController::class);


});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth','admin'])->name('admin.')->group(function(){

    // DASHBOARD
    Route::get('dashboard',[AdminController::class,'dashboard'])->name('dashboard');

    // PRODUCTS
    Route::resource('products', AdminProductController::class);

    // ORDERS
    Route::get('orders',[AdminOrderController::class,'index'])->name('orders.index');

    Route::get('orders/{order}',[AdminOrderController::class,'show'])->name('orders.show');

    Route::post('orders/{order}/status',
        [AdminOrderController::class,'updateStatus']
    )->name('orders.updateStatus');

    // APPROVE PAYMENT
    Route::post('orders/{order}/approve-payment',
        [AdminOrderController::class,'approvePayment']
    )->name('orders.approvePayment');

    // CREATE ORDER (ADMIN)
    Route::get('orders/create',
        [AdminOrderController::class,'create']
    )->name('orders.create');

    Route::post('orders',
        [AdminOrderController::class,'store']
    )->name('orders.store');

});