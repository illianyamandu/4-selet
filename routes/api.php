<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->middleware(['cors'])->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('user.login');

    Route::group(['prefix' => 'users'], function () {
        Route::post('/', [UserController::class, 'create'])->name('users.insert');
        Route::get('/{id?}', [UserController::class, 'list'])->name('users.list');
        Route::patch('/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('users.delete');
    });
});
