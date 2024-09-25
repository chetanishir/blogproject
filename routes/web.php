<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
 

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

Route::get('/', function () {
    return view('layout');
});

// Registration Routes
Route::match(['get', 'post'], 'register', [RegisterController::class, 'register'])->name('register')->middleware('guest');;

// Login Routes
Route::match(['get', 'post'], 'login', [LoginController::class, 'login'])->name('login')->middleware('guest');;
Route::match(['get', 'post'],'logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');;

// Route::resource('blogs', BlogController::class);
Route::middleware('auth')->group(function () {
    Route::resource('blogs', BlogController::class)->except([
        'index', 'show'
    ]);
    Route::post('blogs/import', [BlogController::class, 'import'])->name('blogs.import');
      Route::get('blogs/export', [BlogController::class, 'export'])->name('blogs.export');
});

// Public access to index and show routes
Route::resource('blogs', BlogController::class)->only([
    'index', 'show'
]);


 
