<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->middleware('web')
    ->name('sanctum.csrf-cookie');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/profile-information', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    
    Route::post('/favorites/toggle/{tourId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/api/favorites', [FavoriteController::class, 'index'])->name('api.favorites.index');
    Route::delete('/favorites/remove/{tourId}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    
    Route::get('/api/reservations', [App\Http\Controllers\UserReservationController::class, 'index'])->name('api.reservations.index');
    Route::get('/api/reservations/{id}', [App\Http\Controllers\UserReservationController::class, 'show'])->name('api.reservations.show');
    Route::post('/api/reservations/{id}/cancel', [App\Http\Controllers\UserReservationController::class, 'cancel'])->name('api.reservations.cancel');
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('api/admin')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Admin paneline hoş geldiniz!']);
    });
});

Route::prefix('admin')->group(function () {
    Route::get('/categories-list', [App\Http\Controllers\Admin\TourCategoryController::class, 'index'])->name('admin.categories.list');
    Route::post('/categories-store', [App\Http\Controllers\Admin\TourCategoryController::class, 'store'])->name('admin.categories.store');
    Route::post('/categories-update/{id}', [App\Http\Controllers\Admin\TourCategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('/categories-delete/{id}', [App\Http\Controllers\Admin\TourCategoryController::class, 'destroy'])->name('admin.categories.delete');
    
    Route::get('/tours-list', [App\Http\Controllers\Admin\TourController::class, 'index'])->name('admin.tours.list');
    Route::post('/tours-store', [App\Http\Controllers\Admin\TourController::class, 'store'])->name('admin.tours.store');
    Route::post('/tours-update/{id}', [App\Http\Controllers\Admin\TourController::class, 'update'])->name('admin.tours.update');
    Route::post('/tours-delete/{id}', [App\Http\Controllers\Admin\TourController::class, 'destroy'])->name('admin.tours.delete');
    
    Route::get('/reservations-list', [App\Http\Controllers\Admin\ReservationController::class, 'list'])->name('admin.reservations.list');
    Route::post('/reservations-store', [App\Http\Controllers\Admin\ReservationController::class, 'store'])->name('admin.reservations.store');
    Route::get('/reservations-show/{id}', [App\Http\Controllers\Admin\ReservationController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations-update/{id}', [App\Http\Controllers\Admin\ReservationController::class, 'update'])->name('admin.reservations.update');
    Route::post('/reservations-delete/{id}', [App\Http\Controllers\Admin\ReservationController::class, 'destroy'])->name('admin.reservations.delete');
    Route::post('/reservations-status/{id}', [App\Http\Controllers\Admin\ReservationController::class, 'updateStatus'])->name('admin.reservations.status');
    Route::get('/users-list', [App\Http\Controllers\Admin\ReservationController::class, 'getUsers'])->name('admin.users.list');
    Route::get('/tours-for-reservations', [App\Http\Controllers\Admin\ReservationController::class, 'getTours'])->name('admin.reservations.tours');
    
    Route::get('/users-data', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.data');
    Route::post('/users-store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::post('/users-update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users-delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/roles', [App\Http\Controllers\Admin\UserController::class, 'getRoles'])->name('admin.roles');
});

Route::prefix('api')->group(function () {
    Route::get('/tours', [App\Http\Controllers\TourController::class, 'index']);
    Route::get('/tours/search', [App\Http\Controllers\TourController::class, 'search']);
    Route::get('/tours/{id}', [App\Http\Controllers\TourController::class, 'show']);
    Route::get('/tours/{tour}/reviews', [App\Http\Controllers\TourReviewController::class, 'index']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/tours/{tour}/reviews', [App\Http\Controllers\TourReviewController::class, 'store']);
        Route::put('/reviews/{review}', [App\Http\Controllers\TourReviewController::class, 'update']);
        Route::post('/reviews/{review}/like', [App\Http\Controllers\TourReviewController::class, 'toggleLike']);
        Route::post('/reviews/{review}/reply', [App\Http\Controllers\TourReviewController::class, 'addReply']);
        Route::post('/replies/{reply}/like', [App\Http\Controllers\TourReviewController::class, 'toggleReplyLike']);
    });
    
    Route::post('/tours/create-reservation', [App\Http\Controllers\TourController::class, 'createReservation']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tours/{tour}/reviews', [App\Http\Controllers\TourReviewController::class, 'store']);
    Route::put('/reviews/{review}', [App\Http\Controllers\TourReviewController::class, 'update']);
    Route::post('/reviews/{review}/like', [App\Http\Controllers\TourReviewController::class, 'toggleLike']);
    Route::post('/reviews/{review}/reply', [App\Http\Controllers\TourReviewController::class, 'addReply']);
    Route::post('/replies/{reply}/like', [App\Http\Controllers\TourReviewController::class, 'toggleReplyLike']);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
