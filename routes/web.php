<?php

use App\Http\Controllers\Admin\StaffProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Auth\StaffRegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserTableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserTableController::class, 'index'])->name('user.table');

Route::middleware(['auth','userAuthorized' ,'verified'])->group(function (){
    Route::get('dashboard', [UserTableController::class, 'index'])->name('dashboard');
    //profile related
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
    //food soft delete routes related
    Route::delete('food/remove/{id}',[FoodController::class,'remove'])->name('food.remove');
    Route::get('food/trash',[FoodController::class,'trash'])->name('food.trash.index');
    Route::get('food/back/{id}',[FoodController::class,'back'])->name('food.back');
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
    //middleware specific only for superadmin role
    Route::middleware(['onlySuperAdmin'])->group(function () {
        //staff routes related
        Route::get('staff',[StaffController::class,'index'])->name('staff.index');
        Route::get('staff/register',[StaffRegisterController::class,'create'])->name('staff.create');
        Route::post('staff/register',[StaffRegisterController::class,'store'])->name('staff.register');
        Route::get('staff/edit/{id}',[StaffController::class,'edit'])->name('staff.edit');
        Route::post('staff/update/{id}',[StaffController::class,'update'])->name('staff.store');
        //staff soft delete routes related
        Route::delete('staff/remove/{id}',[StaffController::class,'remove'])->name('staff.remove');
        Route::get('staff/trash',[StaffController::class,'trash'])->name('staff.trash.index');
        Route::get('staff/back/{id}',[StaffController::class,'back'])->name('staff.back');
    });
    //profile routes related
    Route::get('profile',[StaffProfileController::class,'index'])->name('staff.profile.index');
    Route::patch('profile', [StaffProfileController::class, 'update'])->name('staff.profile.update');
});
require __DIR__.'/auth.php';
