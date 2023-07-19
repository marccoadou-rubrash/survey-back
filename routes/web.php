<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', function () {
    return redirect()->route('admin.dashboard');
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('', function () {
        return redirect()->route('admin.login.get');
    });

    Route::view('login', 'admin.auth.login')->name('admin.login.get');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.post');

    Route::view('forgot-password', 'admin.auth.forgot-password')->name('admin.forgot-password.get');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.forgot-password.post');

    Route::view('reset-password', 'admin.auth.reset-password')->name('admin.reset-password.get');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('admin.reset-password.post');
});

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::any('logout', [AuthController::class, 'logout'])->name('admin.logout');


    Route::prefix('surveys')->group(function () {
        Route::get('', [SurveyController::class, 'index'])->name('admin.surveys.list');
        Route::get('create', [SurveyController::class, 'create'])->name('admin.surveys.create');
        Route::post('save', [SurveyController::class, 'save'])->name('admin.surveys.save');
        Route::get('{id}/edit', [SurveyController::class, 'edit'])->name('admin.surveys.edit');
        Route::get('{id}/delete', [SurveyController::class, 'delete'])->name('admin.surveys.delete');
        Route::get('{id}/status/update', [SurveyController::class, 'updateStatus'])->name('admin.surveys.status');
    });

    Route::prefix('companies')->group(function () {
        Route::get('', [CompanyController::class, 'index'])->name('admin.companies.list');
        Route::get('create', [CompanyController::class, 'create'])->name('admin.companies.create');
        Route::post('save', [CompanyController::class, 'save'])->name('admin.companies.save');
        Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('admin.companies.edit');
        Route::get('{id}/delete', [CompanyController::class, 'delete'])->name('admin.companies.delete');
    });

    Route::prefix('results')->group(function () {
        Route::get('', [ResultController::class, 'index'])->name('admin.results.list');
    });
});
