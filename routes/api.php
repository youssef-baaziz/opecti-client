<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\ClientController;
use App\Http\Controllers\Users\SuperadminController;
use App\Http\Controllers\RapportController;

// Define API routes for search
// Route::middleware(['auth:api'])->group(function () {
    Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::get('/rapports/search', [RapportController::class, 'search'])->name('rapports.search');
    Route::get('/users/search', [SuperadminController::class, 'search'])->name('users.search');
// });
Route::get('/rapports/download', [RapportController::class, 'download'])->name('rapports.download');

Route::get('/test', function () {
    return response()->json(['message' => 'Hello, World!']);
})->name('test.hello');
