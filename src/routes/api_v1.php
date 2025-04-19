<?php

use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\TableController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function() {
    
    Route::apiResource('clients', ClientController::class);

    Route::apiResource('tables', TableController::class);
});