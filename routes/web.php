<?php

use App\Http\Controllers\ProductController;
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

Route::get('/search', function () {
    return view('test');
});

Route::get('/search-algolia', function () {
    return view('algolia_search');
});

Route::apiResource('/products', ProductController::class);



// search route

// Route::get('/search-algolia', 'ProductController@searchAlgolia')->name('search-algolia');
