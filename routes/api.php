<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


$board = function(){
    Route::get('/', 'BoardController@index');
    Route::post('/', 'BoardController@create');
    Route::post('/dump/db', 'BoardController@dump');
    Route::patch('/{board}', 'BoardController@update');
    Route::delete('/{board}', 'BoardController@destroy');
};

Route::group(['prefix' => '/boards'],$board);


$task = function(){
    Route::get('/', 'TaskController@index');
    Route::post('/', 'TaskController@create');
    Route::patch('/{Task}', 'TaskController@update');
    Route::patch('/column/{Task}', 'TaskController@updateColumn');
    Route::delete('/{Task}', 'TaskController@destroy');
};

Route::group(['prefix' => '/tasks'],$task);
