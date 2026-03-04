<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UsersController;

// Authentication routes (simple custom login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Sessions (device) management
Route::get('/sessions', [SessionsController::class, 'index'])->name('sessions.index');
Route::post('/sessions/{id}/revoke', [SessionsController::class, 'revoke'])->name('sessions.revoke');

// Revoke all sessions for current user
Route::post('/sessions/revoke-all', [SessionsController::class, 'revokeAll'])->name('sessions.revokeAll');

// Users list
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
