<?php

use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\PhotosController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/users', function () {
        return User::with('albums')->paginate(5);
    });
    Route::resource('/albums', AlbumsController::class);
    Route::get('/', [AlbumsController::class, 'index']);
    Route::get('/albums/{album}/images', [AlbumsController::class, 'getImages'])
        ->name('albums.images');

    Route::resource('photos', PhotosController::class);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
