<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\TourController as PublicTourController;
use App\Http\Controllers\TourReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\ReportController as APIReportController;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\UserReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\API\TourController as APITourController;
use App\Http\Controllers\API\TourReviewController as APITourReviewController;
use App\Http\Controllers\API\AnalyticsController as APIAnalyticsController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminTourController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminUserController as AdminAdminUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/tours', [App\Http\Controllers\TourController::class, 'index']);
Route::get('/tours/{id}', [App\Http\Controllers\TourController::class, 'show']);
Route::get('/tours/{tour}/reviews', [TourReviewController::class, 'index']);
Route::post('/tours/{tour}/reviews', [TourReviewController::class, 'store']);
Route::post('/tours/create-reservation', [App\Http\Controllers\TourController::class, 'createReservation']);
Route::get('/tours/search', [App\Http\Controllers\TourController::class, 'search']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/reviews/{review}/like', [TourReviewController::class, 'toggleLike']);
    Route::post('/reviews/{review}/reply', [TourReviewController::class, 'addReply']);
    Route::post('/replies/{reply}/like', [TourReviewController::class, 'toggleReplyLike']);
});

Route::prefix('admin/reports')->group(function () {
    Route::get('/summary', [APIReportController::class, 'getSummary']);
    Route::get('/monthly-income', [APIReportController::class, 'getMonthlyIncome']);
    Route::get('/top-tours', [APIReportController::class, 'getTopTours']);
    Route::get('/user-demographics', [APIReportController::class, 'getUserDemographics']);
    Route::get('/participant-stats', [APIReportController::class, 'getParticipantStats']);
    Route::post('/export', [APIReportController::class, 'exportReport']);
});

Route::prefix('admin/analytics')->group(function () {
    Route::get('/realtime', [AnalyticsController::class, 'getRealTimeData']);
    Route::get('/recent-reservations', [AnalyticsController::class, 'getRecentReservations']);
    Route::get('/active-users', [AnalyticsController::class, 'getActiveUsers']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::post('/', [ReservationController::class, 'store']);
        Route::get('/{reservation}', [ReservationController::class, 'show']);
        Route::put('/{reservation}', [ReservationController::class, 'update']);
        Route::delete('/{reservation}', [ReservationController::class, 'destroy']);
    });
    
    Route::prefix('reviews')->group(function () {
        Route::post('/', [TourReviewController::class, 'store']);
        Route::put('/{review}', [TourReviewController::class, 'update']);
        Route::delete('/{review}', [TourReviewController::class, 'destroy']);
        Route::post('/{review}/like', [TourReviewController::class, 'toggleLike']);
        Route::post('/{review}/unlike', [TourReviewController::class, 'toggleLike']);
        Route::post('/{review}/reply', [TourReviewController::class, 'addReply']);
        Route::delete('/reply/{reply}', [TourReviewController::class, 'deleteReply']);
    });

    Route::get('/reservations', [UserReservationController::class, 'index'])->name('api.reservations.index');
    Route::get('/reservations/{id}', [UserReservationController::class, 'show'])->name('api.reservations.show');
    Route::post('/reservations/{id}/cancel', [UserReservationController::class, 'cancel'])->name('api.reservations.cancel');

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{tourId}', [FavoriteController::class, 'add']);
    Route::delete('/favorites/{tourId}', [FavoriteController::class, 'remove']);
    
    Route::post('/payments/process', [PaymentController::class, 'processPayment']);
    Route::get('/payments/status/{transactionId}', [PaymentController::class, 'checkPaymentStatus']);
    Route::post('/payments/refund', [PaymentController::class, 'refundPayment']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/reservation-stats', [DashboardController::class, 'getReservationStats']);
    
    Route::prefix('categories')->group(function () {
        Route::get('/', [TourCategoryController::class, 'index']);
        Route::post('/', [TourCategoryController::class, 'store']);
        Route::put('/{category}', [TourCategoryController::class, 'update']);
        Route::delete('/{category}', [TourCategoryController::class, 'destroy']);
    });
    
    Route::prefix('tours')->group(function () {
        Route::get('/', [TourController::class, 'index']);
        Route::post('/', [TourController::class, 'store']);
        Route::get('/{tour}', [TourController::class, 'show']);
        Route::put('/{tour}', [TourController::class, 'update']);
        Route::delete('/{tour}', [TourController::class, 'destroy']);
    });
    
    Route::prefix('reservations')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReservationController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Admin\ReservationController::class, 'store']);
        Route::get('/{reservation}', [\App\Http\Controllers\Admin\ReservationController::class, 'show']);
        Route::put('/{reservation}', [\App\Http\Controllers\Admin\ReservationController::class, 'update']);
        Route::delete('/{reservation}', [\App\Http\Controllers\Admin\ReservationController::class, 'destroy']);
        Route::put('/{reservation}/status', [\App\Http\Controllers\Admin\ReservationController::class, 'updateStatus']);
    });
    
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index']);
        Route::get('/{user}', [AdminUserController::class, 'show']);
        Route::put('/{user}', [AdminUserController::class, 'update']);
        Route::delete('/{user}', [AdminUserController::class, 'destroy']);
    });

    Route::get('/reports/revenue', [ReportController::class, 'revenueReport']);
    Route::get('/reports/tours', [ReportController::class, 'toursReport']);
    Route::get('/reports/reservations', [ReportController::class, 'reservationsReport']);
});