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

Route::get('usersnoalbums', function () {
    $usersnoalbum = DB::table('users  as u')
        ->leftJoin('albums as a', 'u.id', 'a.user_id')
        ->select('u.id', 'email', 'name', 'album_name')->
        whereRaw('album_name is null')
        ->get();
    $usersnoalbum = DB::table('users  as u')
        ->select('u.id', 'email', 'name')->
        whereRaw(' EXISTS (SELECT user_id from albums where user_id= u.id)')
        ->get();
    return $usersnoalbum;
});
