<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\VenueController as OwnerVenueController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
Route::get('/venues/{venue}', [VenueController::class, 'show'])->name('venues.show');
Route::get('/venues/{venue}/slots', [VenueController::class, 'slots'])->name('venues.slots');

Route::middleware(['auth', 'role:customer|admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    // route khusus customer lainnya...
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:customer|admin')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/venues/{venue}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/venues/{venue}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/venues/{venue}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/venues/{venue}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');

    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/mock-checkout', [PaymentController::class, 'mockCheckout'])->name('payments.mock.checkout');
    Route::post('/payments/{payment}/mock-pay', [PaymentController::class, 'mockPay'])->name('payments.mock.pay');
    Route::post('/payments/{payment}/mock-fail', [PaymentController::class, 'mockFail'])->name('payments.mock.fail');
});

Route::middleware(['auth', 'role:venue_owner|admin'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('venues', OwnerVenueController::class)->except(['show', 'destroy']);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
