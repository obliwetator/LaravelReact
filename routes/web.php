<?php
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


// Default page

	Route::post('/test', 'PagesController@test');
	Route::any('/upload', 'PagesController@upload');

	Route::view('/{path?}', 'welcome')->where('path', '.+');

	// Route::get('/summoner', 'PagesController@summoner');

	// Route::any('/summoner/individualGameAjax', 'ajaxController@individualGameAjax');



	// // also ajax change later
	// Route::get('/summoner/champions', 'PagesController@summonerChampions');

	// Route::get('/summoner/champions/ajax/seasonRank', 'ajaxController@seasonRank');
	// Route::get('/summoner/champions/ajax/liveGame', 'ajaxController@summonerLiveGame');
	// Route::post('/summoner/champions/ajax/liveGameRunes','ajaxController@summonerLiveGameRune');

	// Route::get('/champions','PagesController@champions');

	// // Dynamic path for each champion
	// Route::get('/champions/{name}/statistics', 'PagesController@championsStat');

	// Route::get('/stats','PagesController@stats');

	// Route::get('/leaderboards','PagesController@leaderboards');


	// Route::get('/admin', 'PagesController@admin');
	// Route::get('/adminAjax', 'ajaxController@adminAjax');

	// Route::get('/report', 'PagesController@report');
