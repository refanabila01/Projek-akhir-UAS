<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/countries', [CountryController::class, 'index'])
        ->name('countries');

    Route::view('/risk-score', 'risk.index');
    Route::view('/weather-monitoring', 'weather.index');
    Route::view('/currency-dashboard', 'currency.index');
    Route::view('/news-intelligence', 'news.index');
    Route::view('/port-dashboard', 'ports.index');
   
});