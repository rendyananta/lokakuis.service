<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('me', 'ProfileController@me');
        Route::put('me', 'ProfileController@update');
    });
});

Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'user', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', 'UsersController@index');
    Route::get('{user}', 'UsersController@show');
    Route::get('topics', 'UsersController@topics');
});

Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => 'topic', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', 'TopicsController@index');
    Route::post('/', 'TopicsController@store');
    Route::get('{topic}', 'TopicsController@show');
    Route::put('{topic}', 'TopicsController@update');
    Route::patch('{topic}', 'TopicsController@patch');
    Route::delete('{topic}', 'TopicsController@destroy');
    Route::post('{topic}/upload', 'TopicsController@upload');

    Route::group(['prefix' => '{topic}/section'], function () {
        Route::get('/', 'SectionsController@index');
        Route::post('/', 'SectionsController@store');
        Route::get('{section}', 'SectionsController@show');
        Route::put('{section}', 'SectionsController@update');
        Route::delete('{section}', 'SectionsController@destroy');

        Route::group(['prefix' => '{section}/quiz'], function () {
            Route::get('/', 'QuizzesController@index');
            Route::post('/', 'QuizzesController@store');
            Route::get('{quiz}', 'QuizzesController@show');
            Route::put('{quiz}', 'QuizzesController@update');
            Route::delete('{quiz}', 'QuizzesController@destroy');
            Route::post('{quiz}/upload', 'QuizzesController@upload');
        });
    });
});

