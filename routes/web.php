<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\AnalysteController;
use App\Http\Controllers\Users\ClientController;
use App\Http\Controllers\Users\SuperadminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register.form')->middleware('guest');
Route::post('/register', [LoginController::class, 'register'])->name('register')->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/redirect', function () {
    $role = Auth::user()->role;
    return redirect('/' . strtolower($role));
});

Route::middleware(['auth'])->group(function () {
    Route::resource('users', SuperadminController::class);
    Route::resource('rapports', RapportController::class);
    Route::resource('clients', ClientController::class);
    Route::get('/client', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/analyste', [AnalysteController::class, 'index'])->name('analyste.dashboard');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/rapport', [RapportController::class, 'index'])->name('rapport.home');
    Route::get('/', [SuperadminController::class, 'index'])->name('dashboard');
});
