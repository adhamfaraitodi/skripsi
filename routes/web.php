<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','userAuthorized' ,'verified'])->name('dashboard');

Route::prefix('superadmin')->middleware(['auth','authorized','verified'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('superadmin.dashboard');
    //food routes related
    Route::get('food',[FoodController::class,'index'])->name('food.index');
    Route::get('food/create',[FoodController::class,'create'])->name('food.create');
    Route::post('food',[FoodController::class,'store'])->name('food.store');
    Route::get('food/edit/{id}',[FoodController::class,'edit'])->name('food.edit');
    Route::post('food/update/{id}',[FoodController::class,'update'])->name('food.update');
    Route::post('food/destroy/{id}',[FoodController::class,'destroy'])->name('food.destroy');
    Route::post('food/restore/{id}',[FoodController::class,'restore'])->name('food.restore');
    //food category routes related
    Route::get('food/category',[CategoryController::class,'index'])->name('category.index');
    Route::get('food/category/create',[CategoryController::class,'create'])->name('category.create');
    Route::post('food/category',[CategoryController::class,'store'])->name('category.store');
    Route::get('food/category/edit/{id}',[CategoryController::class,'edit'])->name('category.edit');
    Route::post('food/category/update/{id}',[CategoryController::class,'update'])->name('category.update');
    //food inventory routes related
    Route::get('food/inventory',[InventoryController::class,'index'])->name('food.inventory.index');
    Route::post('food/inventory/update/{id}',[InventoryController::class,'update'])->name('inventory.update');
    //order routes related
    Route::get('order-in',[OrderController::class,'index'])->name('order.index');
    Route::post('order/update/{id}',[OrderController::class,'update'])->name('order.update');
    Route::get('order-history',[OrderController::class,'history'])->name('order.history.index');
    //table routes related
    Route::get('table',[TableController::class,'index'])->name('table.index');
    Route::post('table/create',[TableController::class,'create'])->name('table.create');
    //report routes related
    Route::get('sales-report',[ReportController::class,'index'])->name('sales.index');
    Route::get('inventory-report',[ReportController::class,'inventory'])->name('inventory.index');
    Route::get('financial-report',[ReportController::class,'financial'])->name('financial.index');
    //staff routes related
    Route::get('staff',[StaffController::class,'index'])->name('staff.index');
    Route::get('staff/register',[StaffController::class,'create'])->name('staff.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
