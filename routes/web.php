<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\AutoLoginController; // Added for auto-login
use App\Http\Middleware\AttemptLoginViaSharedToken; // Added for auto-login middleware

Route::get('/', function () {
    return view('welcome');
});

// Auto-login route that uses the shared token
Route::get('/auto-login', [AutoLoginController::class, 'performAutoLogin'])
    ->middleware(AttemptLoginViaSharedToken::class) // Apply the token processing middleware first
    ->name('shared.autologin'); // Give it a name for easier reference

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/shared-logout', [App\Http\Controllers\SharedLogoutController::class, 'handleSharedLogout'])->name('shared.logout.foodpanda');

require __DIR__ . '/auth.php';
