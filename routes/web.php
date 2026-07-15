<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Data negara
    Route::get('/countries', [CountryController::class, 'index'])
        ->name('countries');
    Route::get('/countries/{id}', [CountryController::class, 'show'])
        ->name('countries.show');

    // Dashboard lainnya
    Route::get('/risk-score', [RiskController::class, 'index'])
        ->name('risk');

    Route::get('/weather-monitoring', [WeatherController::class, 'index'])
        ->name('weather');

    Route::get('/currency-dashboard', [CurrencyController::class, 'index'])
        ->name('currency');

    Route::get('/news-intelligence', [NewsController::class, 'index'])
        ->name('news');

    Route::get('/port-dashboard', [PortController::class, 'index'])
        ->name('ports');

    Route::get('/chart-dashboard', [DashboardController::class, 'charts'])
        ->name('charts');

    Route::get('/compare-countries', [CompareController::class, 'index'])
        ->name('compare');

    Route::get('/watchlist', [FavoriteController::class, 'index'])
        ->name('watchlist');

    // Admin Panel Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        Route::get('/admin/ports', [AdminController::class, 'ports'])->name('admin.ports');
        Route::post('/admin/ports', [AdminController::class, 'storePort'])->name('admin.ports.store');
        Route::put('/admin/ports/{id}', [AdminController::class, 'updatePort'])->name('admin.ports.update');
        Route::delete('/admin/ports/{id}', [AdminController::class, 'destroyPort'])->name('admin.ports.destroy');

        Route::get('/admin/articles', [AdminController::class, 'articles'])->name('admin.articles');
        Route::post('/admin/articles', [AdminController::class, 'storeArticle'])->name('admin.articles.store');
        Route::put('/admin/articles/{id}', [AdminController::class, 'updateArticle'])->name('admin.articles.update');
        Route::delete('/admin/articles/{id}', [AdminController::class, 'destroyArticle'])->name('admin.articles.destroy');
    });

});