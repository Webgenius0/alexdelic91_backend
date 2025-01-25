<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\Backend\CategoryController;
use App\Http\Controllers\web\Backend\SubcategoryController;

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('admin.categories.index');
    Route::get('/category/create', 'create')->name('admin.categories.create');
    Route::post('/category/store', 'store')->name('admin.categories.store');
    Route::get('/category/edit/{id}', 'edit')->name('admin.categories.edit');
    Route::post('/category/update/{id}', 'update')->name('admin.categories.update');
    Route::delete('/category/delete/{id}', 'destroy')->name('admin.categories.destroy');
    Route::get('/category/status/{id}', 'status')->name('admin.categories.status');
});

Route::controller(SubcategoryController::class)->group(function () {
    Route::get('/subcategory', 'index')->name('admin.subcategories.index');
    Route::get('/subcategory/create', 'create')->name('admin.subcategories.create');
    Route::post('/subcategory/store', 'store')->name('admin.subcategories.store');
    Route::get('/subcategory/edit/{id}', 'edit')->name('admin.subcategories.edit');
    Route::post('/subcategory/update/{id}', 'update')->name('admin.subcategories.update');
    Route::delete('/subcategory/delete/{id}', 'destroy')->name('admin.subcategories.destroy');
    Route::get('/subcategory/status/{id}', 'status')->name('admin.subcategories.status');
});