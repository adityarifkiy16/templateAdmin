<?php

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

Route::get('/', function () {
    return view('role-permission.role.index');
});

Route::group(['middleware' => ['guest']], function () {
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    // Route::resource('users', App\Http\Controllers\UserController::class);
    // Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);
});
