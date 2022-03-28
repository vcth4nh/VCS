<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Exers;
use App\Models\Submitted;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\RotatingFileHandler;
use PHPUnit\Runner\Filter\ExcludeGroupFilterIterator;

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
    Route::prefix('register')->middleware('teacher')->group(function () {
        Route::get('/', [RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('/', [RegisteredUserController::class, 'store']);
    });


    Route::prefix('dashboard')->group(function () {
        Route::get('/', [UsersController::class, 'index'])
            ->name('dashboard.index');
        Route::post('/', [UsersController::class, 'update'])
            ->name('dashboard.update');
        Route::delete('/', [UsersController::class, 'destroy'])
            ->name('dashboard.destroy')->middleware('teacher');
    });

    Route::prefix('exercises')->group(function () {
        Route::post('/upload', [ExersController::class, 'store'])
            ->name('exercises.store');
        Route::get('/download/{path}', [ExersController::class, 'download'])
            ->name('exercises.download');
        Route::delete('/delete', [ExersController::class, 'destroy'])
            ->name('exercises.destroy')->middleware('teacher');
    });

    Route::prefix('submitted')->group(function () {
        Route::get('/{exer_id}', [SubmittedController::class, 'index'])
            ->name('submitted.index');
        Route::post('/upload', [SubmittedController::class, 'store'])
            ->name('submitted.store');
        Route::get('/download/{path}', [SubmittedController::class, 'download'])
            ->name('submitted.download');
    });

    Route::prefix('user-list')->group(function () {
        Route::get('/', [MsgController::class, 'index'])
            ->name('user-list.index');
        Route::get('/msg/{recv_uid?}', [MsgController::class, 'index'])
            ->name('msg.index');
        Route::post('/msg', [MsgController::class, 'store'])
            ->name('msg.store');
        Route::put('/msg', [MsgController::class, 'update'])
            ->name('msg.update');
        Route::delete('/msg', [MsgController::class, 'destroy'])
            ->name('msg.destroy');
    });


    Route::prefix('challenges')->group(function () {
        Route::get('/', [ChallsController::class, 'index'])
            ->name('challenges.index');
        Route::get('/check', [ChallsController::class, 'check'])
            ->name('challenges.check');
        Route::post('/check', [ChallsController::class, 'check'])
            ->name('challenges.check');
        Route::post('/', [ChallsController::class, 'store'])
            ->name('challenges.store');
    });


});

