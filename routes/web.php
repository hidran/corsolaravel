<?php

use App\Http\Controllers\AlbumsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return User::with('albums')->paginate(5);
});
//Route::get('/albums', [AlbumsController::class, 'index']);
Route::resource('/albums', AlbumsController::class);
Route::delete('/albums/{album}/delete', [AlbumsController::class, 'delete']);
Route::get('/', [AlbumsController::class, 'index']);
