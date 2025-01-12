<?php

use App\Http\Controllers\Api\ManufacturerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('manufacturers')->group(function () {
    Route::post('/basic-info', [ManufacturerController::class, 'storeBasicInfo']);
    Route::post('/company-info', [ManufacturerController::class, 'storeCompanyInfo']);
    Route::post('/confirm', [ManufacturerController::class, 'confirm']);
    Route::get('/{user_id}', [ManufacturerController::class, 'getManufacturerDetails']);
    Route::delete('/delete', [ManufacturerController::class, 'deleteManufacturer']);
});
