<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/countries', [ApiController::class, 'indexCountries']);
Route::get('/risk', [ApiController::class, 'indexRisk']);
Route::get('/ports', [ApiController::class, 'indexPorts']);
Route::get('/news', [ApiController::class, 'indexNews']);
Route::get('/currency', [ApiController::class, 'indexCurrency']);
