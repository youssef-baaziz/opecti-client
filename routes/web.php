<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\AnalysteController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IOCController;
use App\Http\Controllers\Users\SuperadminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register.form')->middleware('guest');
Route::post('/register', [LoginController::class, 'register'])->name('register')->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/redirect', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return redirect('/' . strtolower($role));
    }
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('users', SuperadminController::class);
    Route::resource('rapports', RapportController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('alerts', AlertController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('iocs', IOCController::class);

    Route::get('/user', [SuperadminController::class, 'index'])->name('user.home');
    Route::get('/rapport', [RapportController::class, 'index'])->name('rapport.home');
    Route::get('/client', [ClientController::class, 'index'])->name('client.home');
    Route::get('/alert', [AlertController::class, 'index'])->name('alert.home');
    Route::get('/comment', [CommentController::class, 'index'])->name('comment.home');
    Route::get('/ioc', [IOCController::class, 'index'])->name('ioc.home');
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('client-dh');

    Route::post('/users', [SuperadminController::class, 'store'])->name('users.store');
    Route::post('/rapports', [RapportController::class, 'store'])->name('rapports.store');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/iocs', [IOCController::class, 'store'])->name('iocs.store');

    Route::get('/client', [ClientController::class, 'dashboard2'])->name('client.dashboard');
    Route::get('/analyste', [AnalysteController::class, 'index'])->name('analyste.dashboard');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/', [SuperadminController::class, 'index'])->name('dashboard');
});
