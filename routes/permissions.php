<?php

use Illuminate\Support\Facades\Route;


Route::resource('/permissions', App\Http\Controllers\PermissionsController::class)->names([
    'index' => 'permissions.index',
    'store' => 'permissions.store',
    'update' => 'permissions.update',
    'destroy' => 'permissions.destroy',
])->except(['show']);
Route::post('/permissions/destroyMany', [App\Http\Controllers\PermissionsController::class, 'destroyMany'])->name('permissions.destroyMany');
Route::get('/permissions/getCurrentPermissionId', [App\Http\Controllers\PermissionsController::class, 'getCurrentPermissionId'])->name('permissions.getCurrentPermissionId');