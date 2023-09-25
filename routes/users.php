<?php

use Illuminate\Support\Facades\Route;


Route::resource('/users', App\Http\Controllers\UsersController::class)->names([
    'index' => 'users.index',
    'store' => 'users.store',
    'update' => 'users.update',
    'destroy' => 'users.destroy',
])->except(['show']);

Route::get('/users/getNextUserId', [App\Http\Controllers\UsersController::class, 'getNextUserId'])->name('users.getNextUserId');