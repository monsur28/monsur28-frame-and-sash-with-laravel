<?php

use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ResellerController;
use App\Http\Controllers\Api\SiteInfoController;
use App\Http\Controllers\Api\ManufacturerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//manufacturers
Route::prefix('manufacturers')->group(function () {
    // Route::post('/basic-info', [ManufacturerController::class, 'storeBasicInfo']);
    // Route::post('/company-info', [ManufacturerController::class, 'storeCompanyInfo']);
    // Route::post('/confirm', [ManufacturerController::class, 'confirm']);
    Route::post('/store', [ManufacturerController::class, 'storeManufacturer']);
    Route::get('/{user_id}', [ManufacturerController::class, 'getManufacturerDetails']);
    Route::delete('/delete', [ManufacturerController::class, 'deleteManufacturer']);
});

//resellers
Route::prefix('resellers')->group(function () {
    // Route::post('/basic-info', [ResellerController::class, 'storeBasicInfo']);
    // Route::post('/company-info', [ResellerController::class, 'storeCompanyInfo']);
    // Route::post('/confirm', [ResellerController::class, 'confirm']);
    Route::post('/store', [ResellerController::class, 'storeReseller']);
    Route::get('/{user_id}', [ResellerController::class, 'getResellerDetails']);
    Route::delete('/delete', [ResellerController::class, 'deleteReseller']);
});

//blogs
Route::apiResource('blogs', BlogController::class);

//contacts
Route::apiResource('contacts', ContactController::class);

//site-info
Route::get('/site-info', [SiteInfoController::class, 'index']);
Route::post('/site-info', [SiteInfoController::class, 'store']);

//discount
Route::get('/discounts', [DiscountController::class, 'index']);
Route::post('/discounts', [DiscountController::class, 'store']);
Route::put('/discounts/{id}', [DiscountController::class, 'update']);
Route::delete('/discounts/{id}', [DiscountController::class, 'destroy']);
