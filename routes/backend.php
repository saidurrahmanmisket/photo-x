<?php

use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\KioskController;
use App\Http\Controllers\Web\Backend\FrameController;
use App\Http\Controllers\Web\Backend\EffectController;
use App\Http\Controllers\Web\Backend\PhotoController;
use App\Http\Controllers\Web\Backend\PaymentController;
use App\Http\Controllers\Web\Backend\CustomerController;
use App\Http\Controllers\Web\Backend\BookingController;
use App\Http\Controllers\Web\Backend\AdvertisementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

//FAQ Routes
Route::controller(FaqController::class)->group(function () {
    Route::get('/faqs', 'index')->name('admin.faqs.index');
    Route::get('/faqs/create', 'create')->name('admin.faqs.create');
    Route::post('/faqs/store', 'store')->name('admin.faqs.store');
    Route::get('/faqs/edit/{id}', 'edit')->name('admin.faqs.edit');
    Route::post('/faqs/update/{id}', 'update')->name('admin.faqs.update');
    Route::post('/faqs/status/{id}', 'status')->name('admin.faqs.status');
    Route::post('/faqs/destroy/{id}', 'destroy')->name('admin.faqs.destroy');
});

// Kiosk Management
Route::resource('kiosks', KioskController::class, ['as' => 'admin']);
// Kiosk status toggle
Route::post('kiosks/status/{id}', [KioskController::class, 'status'])->name('admin.kiosks.status');
// Frame Management
Route::resource('frames', FrameController::class, ['as' => 'admin']);
// Effect Management
Route::resource('effects', EffectController::class, ['as' => 'admin']);
// Photo Management
Route::resource('photos', PhotoController::class, ['as' => 'admin']);
// Payment Management (view only: index, show)
Route::resource('payments', PaymentController::class, ['as' => 'admin'])->only(['index', 'show']);

// Customer Management
Route::controller(CustomerController::class)->group(function () {
    Route::get('/customers', 'index')->name('admin.customers.index');
    Route::get('/customers/create', 'create')->name('admin.customers.create');
    Route::post('/customers/store', 'store')->name('admin.customers.store');
    Route::get('/customers/edit/{id}', 'edit')->name('admin.customers.edit');
    Route::post('/customers/update/{id}', 'update')->name('admin.customers.update');
    Route::post('/customers/status/{id}', 'status')->name('admin.customers.status');
    Route::post('/customers/destroy/{id}', 'destroy')->name('admin.customers.destroy');
});

// Booking Management
Route::controller(BookingController::class)->group(function () {
    Route::get('/bookings', 'index')->name('admin.bookings.index');
    Route::get('/bookings/create', 'create')->name('admin.bookings.create');
    Route::post('/bookings/store', 'store')->name('admin.bookings.store');
    Route::get('/bookings/show/{id}', 'show')->name('admin.bookings.show');
    Route::get('/bookings/edit/{id}', 'edit')->name('admin.bookings.edit');
    Route::post('/bookings/update/{id}', 'update')->name('admin.bookings.update');
    Route::post('/bookings/destroy/{id}', 'destroy')->name('admin.bookings.destroy');
    Route::get('/bookings/settings', 'settings')->name('admin.bookings.settings');
    Route::post('/bookings/settings/update', 'updateSettings')->name('admin.bookings.settings.update');
});

// Advertisement Management
Route::resource('advertisements', AdvertisementController::class, ['as' => 'admin']);
Route::post('advertisements/media/destroy/{id}', [AdvertisementController::class, 'destroyMedia'])->name('admin.advertisements.media.destroy');

// Theme Management
Route::resource('themes', ThemeController::class, ['as' => 'admin']);
