<?php

use App\Http\Controllers\AssetManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/demo', [AssetManagementController::class, 'index']);
Route::get('/create', [AssetManagementController::class, 'create']);
