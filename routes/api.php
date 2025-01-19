<?php

use App\Models\Reseller;
use Illuminate\Http\Request;
use App\Http\Middleware\JwtAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ResellerController;
use App\Http\Controllers\Api\SiteInfoController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ProductCategoryController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']) ->middleware('auth.jwt');
// Route::get('/test-login', [AuthController::class, 'testLogin'])->middleware('auth.jwt');

//product category
Route::apiResource('product-categories', ProductCategoryController::class);

//manufacturers
Route::prefix('manufacturers')->group(function () {
    // Route::post('/basic-info', [ManufacturerController::class, 'storeBasicInfo']);
    // Route::post('/company-info', [ManufacturerController::class, 'storeCompanyInfo']);
    // Route::post('/confirm', [ManufacturerController::class, 'confirm']);
    Route::get('', [ManufacturerController::class, 'getAllManufacturers']);
    Route::post('/store', [ManufacturerController::class, 'storeManufacturer']);
    Route::get('/{user_id}', [ManufacturerController::class, 'getManufacturerDetails']);
    Route::delete('/delete/{user_id}', [ManufacturerController::class, 'deleteManufacturer']);
});

//resellers
Route::prefix('resellers')->group(function () {
    // Route::post('/basic-info', [ResellerController::class, 'storeBasicInfo']);
    // Route::post('/company-info', [ResellerController::class, 'storeCompanyInfo']);
    // Route::post('/confirm', [ResellerController::class, 'confirm']);
    Route::get('', [ResellerController::class, 'getAllResellers']);
    Route::post('/store', [ResellerController::class, 'storeReseller']);
    Route::get('/{user_id}', [ResellerController::class, 'getResellerDetails']);
    Route::delete('/delete/{user_id}', [ResellerController::class, 'deleteReseller']);
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

//packages
Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'index']); // Get all packages with features
    Route::get('/{id}', [PackageController::class, 'show']); // Get a single package with features
    Route::post('/', [PackageController::class, 'store']); // Create a package with features
    Route::put('/{id}', [PackageController::class, 'update']); // Update a package with features
    Route::delete('/{id}', [PackageController::class, 'destroy']); // Delete a package and its features
});


