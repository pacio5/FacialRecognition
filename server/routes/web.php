<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [App\Http\Controllers\AuthorizedController::class, 'dashboard'])->name('dashboard');
Route::get('/authorized/index', [App\Http\Controllers\AuthorizedController::class, 'index'])->name('authorized.index');
Route::put('/authorized/{authorized_faces}/revoke', [App\Http\Controllers\AuthorizedController::class, 'revokeAuthorization'])->name('authorized.revokeAuthorization');
Route::put('/authorized/{authorized_faces}/authorize', [App\Http\Controllers\AuthorizedController::class, 'newAuthorize'])->name('authorized.authorize');
Route::delete('/authorized/{authorized_faces}/delete', [App\Http\Controllers\AuthorizedController::class, 'destroy'])->name('authorized.delete');
Route::get('/authorized/{authorized_faces}/show', [App\Http\Controllers\AuthorizedController::class, 'show'])->name('authorized.show');
Route::get('/authorized/create', [App\Http\Controllers\AuthorizedController::class, 'create'])->name('authorized.create');
Route::post('/authorized/store', [App\Http\Controllers\AuthorizedController::class, 'store'])->name('authorized.store');
Route::get('/access/index', [App\Http\Controllers\AccessAttemptController::class, 'index'])->name('attempts.index');



Auth::routes();

