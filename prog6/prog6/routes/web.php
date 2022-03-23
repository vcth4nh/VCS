<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
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

require __DIR__ . '/auth.php';

Route::redirect('/', 'login');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [UsersController::class, 'index'])->name('dashboard.index');
    Route::get('user-list', [MsgController::class, 'index'])->name('user-list.index');
    Route::view('challenges', 'challenges')->name('challenges.index');

    Route::post('dashboard', [UsersController::class, 'store'])->name('dashboard.store');
    Route::post('dashboard/avatar', [AvatarController::class, 'store'])->name('avatar.store');

    Route::middleware('teacher')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
        Route::delete('dashboard', [UsersController::class, 'destroy'])->name('dashboard.destroy')
            ->middleware('teacher');

    });
});

