<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\InventoryController;
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
    Route::get('food/inventory',[InventoryController::class,'index'])->name('inventory.index');
    Route::post('food/inventory/update/{id}',[InventoryController::class,'update'])->name('inventory.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
