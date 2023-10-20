<?php

use Illuminate\Support\Facades\Route;


Route::resource('/tags', App\Http\Controllers\TagsController::class)->names([
    'index' => 'tags.index',
    'store' => 'tags.store',
    'update' => 'tags.update',
    'destroy' => 'tags.destroy',
])->except(['show']);
Route::post('/tags/destroyMany', [App\Http\Controllers\TagsController::class, 'destroyMany'])->name('tags.destroyMany');
Route::get('/tags/getCurrentTagId', [App\Http\Controllers\TagsController::class, 'getCurrentTagId'])->name('tags.getCurrentTagId');