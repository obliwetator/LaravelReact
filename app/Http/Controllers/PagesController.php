<?php

namespace App\Http\Controllers;

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;
use Illuminate\Http\Request;

class PagesController extends Controller
{
	public function home()
	{
		return view('welcome');
	}

	public function test()
	{
		return view('test');
	}

	public function report()
	{
		return view('report');
	}

	public function Summoner(Request $name)
	{
		clock()->startEvent("SummonerController", "Time spent in summoner controller");
		$summonerName = $name->get("name");
		$region = $name->get("region");

		// If no username is entered return 404
		if (!isset($summonerName)) {
			return abort(404);
		}
		// Init
		$lol = new LeagueAPI();
		$db = new dbCall();

		// $region = 'eun1';
		$locale = 'en_GB';
		$version = '9.17.1';
		$limit = 2;


		clock()->startEvent("GetDbSummoner", "Load Summoner from db");
		$summoner = $db->getSummoner($region, $summonerName);
		clock()->endEvent("GetDbSummoner");
		if (isset($summoner)) {
			
		}
		else{
			return abort(404);
		}
		clock()->startEvent("GetDbMatchlist", "Load Matchlist from db");
		// TODO: Store NULL matchlist in DB so that we wont reuqry the api every time (low priority)
		$matchlist = $db->getMatchlist($region, $summoner->accountId, $limit);
		clock()->endEvent("GetDbMatchlist");
		


		
		clock()->startEvent("getStaticData", "Load all the static data");

		// Load all static data for that specific page?
		clock()->startEvent("getStaticProfileIcons", "getStaticProfileIcons");
		$icons = $lol->getStaticProfileIcons($locale, $version);
		clock()->endEvent("getStaticProfileIcons");

		clock()->startEvent("getStaticSummonerSpells", "getStaticSummonerSpells");
		$summonerSpells = $lol->getStaticSummonerSpells($locale, $version);
		clock()->endEvent("getStaticSummonerSpells");

		clock()->startEvent("getStaticChampions", "getStaticChampions");
		$staticChampions  =  $lol->getStaticChampions(true, $locale, $version);
		clock()->endEvent("getStaticChampions");

		clock()->startEvent("getStaticItems", "getStaticItems");
		$staticItems =  $lol->getStaticItems($locale, $version);
		clock()->endEvent("getStaticItems");

		clock()->startEvent("getStaticRunesReforged", "getStaticRunesReforged");
		$staticRunes = $lol->getStaticRunesReforged($locale, $version);
		clock()->endEvent("getStaticRunesReforged");


		clock()->endEvent("getStaticData");


		clock()->startEvent("getMatchbyId", "Load Match by id from Db");
		if (isset($matchlist)) {
			$matchById = $db->getMatchById($region, $matchlist);
		}
		else{
			$matchById = null;
		}
		clock()->endEvent("getMatchbyId");

		clock()->startEvent("getLeagueSummoner", "Load LeagueSummoner");
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


		clock()->endEvent("getLeagueSummoner");

		clock()->endEvent("SummonerController");
		return response()->json($summoner);
		clock()->startEvent("View", "Create/send view");
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
		clock()->startEvent("champions", "champions list controller");

		clock()->startEvent("getContents", "Get Contents");
		$file = file_get_contents('lolContent/data/en_GB/championFull.json');
		clock()->endEvent("getContents");

		clock()->startEvent("decode", "Decode");
		$file = json_decode($file, true);
		clock()->endEvent("decode");

		clock()->endEvent("champions");

		clock()->startEvent("view", "champions list view");
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
