<?php

use App\Models\Album;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/users', function () {
    return User::with('albums')->paginate(5);
});
Route::get('/albums', function () {
    return Album::with('photos')->paginate(5);
});
Route::get('/', function () {
    return view('welcome');
});
