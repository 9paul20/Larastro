<?php

use Illuminate\Support\Facades\Route;


Route::resource('/users', App\Http\Controllers\UsersController::class)->names([
    'index' => 'users.index',
    'store' => 'users.store',
    'update' => 'users.update',
    'destroy' => 'users.destroy',
])->except(['show']);
Route::post('/users/destroyMany', [App\Http\Controllers\UsersController::class, 'destroyMany'])->name('users.destroyMany');
Route::get('/users/getCurrentUserId', [App\Http\Controllers\UsersController::class, 'getCurrentUserId'])->name('users.getCurrentUserId');