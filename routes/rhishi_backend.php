<?php

use App\Http\Controllers\Web\Backend\BannerController;
use Illuminate\Support\Facades\Route;


Route::controller(BannerController::class)->group(function () {
    Route::get('/banners', 'index')->name('admin.banner.index');
    Route::get('/banner/create', 'create')->name('admin.banner.create');
    Route::post('/banner/store', 'store')->name('admin.banner.store');
    Route::get('/banner/edit/{id}', 'edit')->name('admin.banner.edit');
    Route::post('/banner/update/{id}', 'update')->name('admin.banner.update');
    Route::delete('/banner/delete/{id}', 'destroy')->name('admin.banner.destroy');
    Route::get('/banner/status/{id}', 'status')->name('admin.banner.status');
});
