<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\SubcategoryController;

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

Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', 'index')->name('admin.faq.index');
    Route::get('/faq/create', 'create')->name('admin.faq.create');
    Route::post('/faq/store', 'store')->name('admin.faq.store');
    Route::get('/faq/edit/{id}', 'edit')->name('admin.faq.edit');
    Route::post('/faq/update/{id}', 'update')->name('admin.faq.update');
    Route::delete('/faq/delete/{id}', 'destroy')->name('admin.faq.destroy');
    Route::post('/faq/status/{id}', 'status')->name('admin.faq.status');
});