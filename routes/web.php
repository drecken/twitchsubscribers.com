<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SubscribersController;
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

Route::get('/', [SiteController::class, 'index'])->name('index');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('login/twitch', [LoginController::class, 'redirectToProvider'])->name('login-twitch');
Route::get('login/twitch/callback', [LoginController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(
    function () {
        Route::get('user', [SiteController::class, 'user'])->name('user');
        Route::get('subscribers', [SubscribersController::class, 'index'])->name('subscribers.index');
        Route::get('fetch-subscribers', [SubscribersController::class, 'fetch'])->name('subscribers.fetch');
        Route::get('synchronize-subscribers', [SubscribersController::class, 'synchronize'])
            ->name('subscribers.synchronize');
    }
);
