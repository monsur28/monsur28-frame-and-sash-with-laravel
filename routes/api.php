<?php

use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ResellerController;
use App\Http\Controllers\Api\ManufacturerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('manufacturers')->group(function () {
    // Route::post('/basic-info', [ManufacturerController::class, 'storeBasicInfo']);
    // Route::post('/company-info', [ManufacturerController::class, 'storeCompanyInfo']);
    // Route::post('/confirm', [ManufacturerController::class, 'confirm']);
    Route::post('/store', [ManufacturerController::class, 'storeManufacturer']);
    Route::get('/{user_id}', [ManufacturerController::class, 'getManufacturerDetails']);
    Route::delete('/delete', [ManufacturerController::class, 'deleteManufacturer']);
});
Route::prefix('resellers')->group(function () {
    Route::post('/basic-info', [ResellerController::class, 'storeBasicInfo']);
    Route::post('/company-info', [ResellerController::class, 'storeCompanyInfo']);
    Route::post('/confirm', [ResellerController::class, 'confirm']);
    Route::get('/{user_id}', [ResellerController::class, 'getResellerDetails']);
    Route::delete('/delete', [ResellerController::class, 'deleteReseller']);
});

Route::apiResource('blogs', BlogController::class);
