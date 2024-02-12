<?php

// generate-module-append-controller
use App\Http\Controllers\Example\ItemController;
use Illuminate\Support\Facades\Route;

// Admin-side APIs
Route::middleware(['auth:api'])->prefix('admin/example')->group(function () {
    // generate-module-append-route-admin
	Route::genericApiResource('items', ItemController::class);
});

// Client-side APIs
Route::middleware(['auth:api'])->prefix('client/example')->group(function () {
    // generate-module-append-route-client
});