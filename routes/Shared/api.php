<?php

// generate-module-append-controller
use Illuminate\Support\Facades\Route;

// Admin-side APIs
Route::middleware(['auth:api'])->prefix('admin/shared')->group(function () {
    // generate-module-append-route-admin
});

// Client-side APIs
Route::middleware(['auth:api'])->prefix('client/shared')->group(function () {
    // generate-module-append-route-client
});