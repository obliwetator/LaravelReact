<?php

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

Route::get('/summoner', 'ApiController@summoner');
Route::post('/getItems', 'ApiController@getItems');
Route::post('/getIcons', 'ApiController@getIcons');
Route::post('/getSummonerSpell', 'ApiController@getSummonerSpells');
Route::post('/getChampions', 'ApiController@getChampions');
Route::post('/getSummonerLeagueTarget', 'ApiController@GetSummonerLeagueTarget');
Route::post('/getRunes', 'ApiController@getRunes');
Route::post('/getSummonerLiveGame', 'ApiController@getSummonerLiveGame');
Route::post('/lookup', 'ApiController@lookup');
Route::post('/getLeagues', 'ApiController@getLeagues');
Route::post('/getInit', 'ApiController@GetInit');

Route::post('/getMatchlist', 'ApiController@getMatchlist');