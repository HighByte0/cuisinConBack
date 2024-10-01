<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodsController;
use App\Models\OrderDetail;

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

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');


require __DIR__.'/auth.php';

Auth::routes();
Route::middleware('web')->group(function () {
    // Define routes that require web middleware
    Auth::routes();
});

Route::get('/',[AuthController::class, 'showLoginForm'])->name('login.form');

// Handle login requests
Route::post('login', [AuthController::class, 'login'])->name('login');

// Show the registration form
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register.form');

// Handle registration requests
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Route for showing the form to add food
Route::get('food/add', [FoodsController::class, 'create'])->name('food.create')->middleware('auth');

// Route for storing new food
Route::post('food/store', [FoodsController::class, 'store'])->name('food.store');


Route::get('/order_detail/{foodUserId}', [OrderController::class, 'orderController'])->name('orders')->middleware('auth'); 

Route::post('/orders/{order}/status', [OrderController::class, 'updateOrderStatus'])->name('orders.updateStatus');
Route::get('/menu/{id}', [OrderController::class, 'menu'])->name('menu');
Route::delete('del/{id}', [OrderController::class, 'destroy'])->name('food.destroy');
Route::get('set', [AuthController::class, 'setting'])->name('setting');
Route::put('/chef', [AuthController::class, 'update'])->name('update');
Route::get('/update-food-status/{id}', [FoodsController::class, 'updateStatus'])->name('updateFoodStatus');
Route::Post('/update-food-status/{id}', [FoodsController::class, 'updateStatusAdmin'])->name('updateFoodStatus');

Route::get('/admin/users/change-status/{id}', [AuthController::class, 'changeStatus'])->name('admin.users.change-status');



// routes/web.php
// Route::get('/menu/{id}', [FoodController::class, 'show'])->name('food.show');



// Route::get('/csrf-token', function () {
//     return response()->json(['csrf_token' => csrf_token()]);
// });
