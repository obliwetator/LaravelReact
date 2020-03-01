<?php

namespace App\Http\Controllers;

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
	public function test(Request $request)
	{
		if ($url = $request->input('url')) {
			$name = md5($url).".png";
			// $name = explode("/" , $url);
			// check if image exists
			if (Storage::exists("files/$name.png")) {
				return response()->json("Already exists");
			}
			$contents = file_get_contents($url);
			Storage::disk('local')->put("files/$name.png", $contents);

			$path = Storage::disk('local')->path("files/$name.png");
			return response()->file($path, ['Content-Type' => 'image/jpeg']);
		}
		else {
			$uploadedFile = $request->file('file');
			Storage::put("/files", $uploadedFile);

			// $request->file('file')->storeAs("files", "$name.$ext");
		}
		return response()->json("ok");
	}
	public function upload()
	{
		return view ('upload');
	}

	public function home()
	{
		return view('welcome');
	}

	public function report()
	{
		return view('report');
	}

	public function Summoner(Request $name)
	{
		$summonerName = $name->get("name");
		$region = $name->get("region");

		// If no username is entered return 404
		if (!isset($summonerName)) {
			return abort(404);
		}
		// Init
		$lol = new LeagueAPI([]);
		$db = new dbCall([
			dbCall::SET_REGION => $region
		]);

		// $region = 'eun1';
		$locale = 'en_GB';
		$version = '9.17.1';
		$limit = 2;

		$summoner = $db->getSummoner($region, $summonerName);

		if (isset($summoner)) {
			
		}
		else{
			return abort(404);
		}
		// TODO: Store NULL matchlist in DB so that we wont reuqry the api every time (low priority)
		$matchlist = $db->getMatchlist($region, $summoner->accountId, $limit);
		
		// Load all static data for that specific page?
		$icons = $lol->getStaticProfileIcons($locale, $version);

		$summonerSpells = $lol->getStaticSummonerSpells($locale, $version);

		$staticChampions  =  $lol->getStaticChampions(true, $locale, $version);
		$staticItems =  $lol->getStaticItems($locale, $version);
		$staticRunes = $lol->getStaticRunesReforged($locale, $version);
		if (isset($matchlist)) {
			$matchById = $db->getMatchById($region, $matchlist);
		}
		else{
			$matchById = null;
		}
		// find all the summonners from the games
		if (isset($matchById)) {
			foreach ($matchById as $key => $value) {
				foreach ($value->participantIdentities as $key2 => $value2) {
					$summonerNameName[$key2] = $value2->player->summonerName;
				}
				$summonerNameObj[$key] = $summonerNameName;
			}
		}
		else{
			$summonerNameObj = null;
		}
		if (isset($matchlist)) {
			$summonerLeagueTarget = $db->getLeagueSummonerSingle($region, $summoner->id);
		}else{
			$summonerLeagueTarget = null;
		}


		return response()->json($summoner);
		return view('summoner')
		->with(['summoner' => $summoner])
		->with(['icons' => $icons])
		->with(['matchById' => $matchById])
		->with(['summonerSpells' => $summonerSpells])
		->with(['champions' => $staticChampions])
		->with(['items' => $staticItems])
		->with(['runes' => $staticRunes])
		->with(['summonerLeagueTarget' => $summonerLeagueTarget])
		->with(['sumonerNameObj' => $summonerNameObj]);
	}

	

	public function summonerChampions(Request $name)
	{
		$summonerName = $name->get("name");

		return view('summonerChampions')->with(['summonerName' => $summonerName]);
	}

	public function champions()
	{
		$file = file_get_contents('lolContent/data/en_GB/championFull.json');
		$file = json_decode($file, true);
		return view('champions')->with(['champions' => $file]);
	}

	public function stats()
	{
		return view('stats');
	}

	public function leaderboards()
	{
		return view('leaderboards');
	}

	// The returned view will be dynamically created depending on the champion name selected
	public function championsStat($name)
	{
		// We will return this view on first request with the default the latest season
		return view('championStats')->with(['name' => $name]);
	}

	public function admin()
	{

		return view('admin');
	}
}
