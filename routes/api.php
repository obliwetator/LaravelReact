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

Route::get('test', function(){
	return response()->json(array('test1' => 1, 'test2' => 2));
});

Route::get('/summoner', 'ApiController@summoner');
Route::post('/getItems', 'ApiController@getItems');
Route::post('/getIcons', 'ApiController@getIcons');