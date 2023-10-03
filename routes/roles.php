<?php

use Illuminate\Support\Facades\Route;


Route::resource('/roles', App\Http\Controllers\RolesController::class)->names([
    'index' => 'roles.index',
    'store' => 'roles.store',
    'update' => 'roles.update',
    'destroy' => 'roles.destroy',
])->except(['show']);
Route::post('/roles/destroyMany', [App\Http\Controllers\RolesController::class, 'destroyMany'])->name('roles.destroyMany');
Route::get('/roles/getCurrentRoleId', [App\Http\Controllers\RolesController::class, 'getCurrentRoleId'])->name('roles.getCurrentRoleId');