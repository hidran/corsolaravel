<?php

use App\Http\Controllers\AlbumsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return User::with('albums')->paginate(5);
});
Route::resource('/albums', AlbumsController::class);
Route::get('/', [AlbumsController::class, 'index']);
Route::get('/albums/{album}/images', [AlbumsController::class, 'getImages'])
    ->name('albums.images');


