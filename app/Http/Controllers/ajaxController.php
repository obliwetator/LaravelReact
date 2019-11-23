<?php

namespace App\Http\Controllers;

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;
use Illuminate\Http\Request;

class ajaxController extends Controller
{

	public function IndividualGameAjax(Request $name)
	{
		clock()->startEvent("IndividualGameAjax", "Time spent in IndividualGameAjax controller");

		$gameId = $name->get("gameId");


		// Init
		$lol = new LeagueAPI();
		$db = new dbCall();

		// Const?
		$region = 'eun1';
		$locale = 'en_GB';
		$version = '9.17.1';
		$limit = 2;
		
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

		clock()->startEvent("getStaticReforgedRunePaths", "getStaticReforgedRunePaths");
		$staticRunesPaths = $lol->getStaticReforgedRunePaths($locale, $version);
		clock()->endEvent("getStaticReforgedRunePaths");



		clock()->startEvent("getMatchbyId", "Load Match by id");
		$matchById = $db->getMatchByIdSingle($region, $gameId);
		clock()->endEvent("getMatchbyId");
		// find all the summonners from the games


		clock()->startEvent("getLeagueSummoner", "Load LeagueSummoner");
		foreach ($matchById as $key => $value) {
			foreach ($value->participantIdentities as $key2 => $value2) {
				$summonerNameId[$key2] = $value2->player->summonerId;
				$summonerNameName[$key2] = $value2->player->summonerName;
			}
			$summonerNameIdObj[$key] = $summonerNameId;
			$summonerNameObj[$key] = $summonerNameName;
		}
		// --------------------------------------------- // 

		$summonerLeague = $db->getLeagueSummoner($region, $summonerNameIdObj);
		clock()->endEvent("getLeagueSummoner");
		/** Returning the pure html seems to take less space the the json encoded version. Further researchw required? 
		 * Upon removing spaces and tabs from the blade template the size dropped to the equivalent of the original html view
		 * Maybe json encodes each space individually?
		*/
		// $serialized = view('individualGameAjax')->with(['matchById' => $matchById])
		// ->with(['summonerSpells' => $summonerSpells])
		// ->with(['champions' => $staticChampions])
		// ->with(['items' => $staticItems])
		// ->with(['runes' => $staticRunes])
		// ->with(['summonerLeague' => $summonerLeague])
		// ->with(['sumonerNameObj' => $summonerNameObj])
		// ->render();

		// return response()->json(array('success' => true, 'html' => $serialized));

		clock()->endEvent("IndividualGameAjax");

		clock()->startEvent("View", "Create/send view");
		return view('individualGameAjax')
		->with(['matchById' => $matchById])
		->with(['summonerSpells' => $summonerSpells])
		->with(['champions' => $staticChampions])
		->with(['items' => $staticItems])
		->with(['runes' => $staticRunes])
		->with(['summonerLeague' => $summonerLeague])
		->with(['sumonerNameObj' => $summonerNameObj])
		->with(['runesPaths' => $staticRunesPaths]);
	}

	public function seasonRank(Request $name)
	{
		return view("individualSeason");
	}
	public function summonerLiveGame(Request $name)
	{
		clock()->startEvent("summonerLiveGame", "Time spent in summonerLiveGame controller");

		$summonerName = $name->get("name");

		// Init
		$lol = new LeagueAPI();
		$db = new dbCall();

		// Const?
		$region = 'eun1';
		$locale = 'en_GB';
		$version = '9.17.1';
		$limit = 2;
		$summoner = $db->getSummoner($region, $summonerName);

		clock()->startEvent("getActiveMatchInfo", "getActiveMatchInfo");
		$activeGame = $lol->getActiveMatchInfo($region, $summoner->id);
		clock()->endEvent("getActiveMatchInfo");
		
		// If the summoner is not in a game return a view that says so.
		if (!isset($activeGame)) {
			clock()->startEvent("noLivegame", "No Live Game View");
			return view('noLiveGame')
			->with(['summoner' => $summonerName]);
		}

		
		clock()->startEvent("getStaticChampions", "getStaticChampions");
		$staticChampions  =  $lol->getStaticChampions(true, $locale, $version);
		clock()->endEvent("getStaticChampions");
		
		clock()->startEvent("getStaticSummonerSpells", "getStaticSummonerSpells");
		$summonerSpells = $lol->getStaticSummonerSpells($locale, $version);
		clock()->endEvent("getStaticSummonerSpells");

		clock()->startEvent("getStaticRunesReforged", "getStaticRunesReforged");
		$staticRunes = $lol->getStaticRunesReforged($locale, $version);
		clock()->endEvent("getStaticRunesReforged");

		clock()->startEvent("getStaticReforgedRunePaths", "getStaticReforgedRunePaths");
		$staticRunesPaths = $lol->getStaticReforgedRunePaths($locale, $version);
		clock()->endEvent("getStaticReforgedRunePaths");

		foreach ($activeGame->participants as $key => $value) {
			$summonerNameIdObj[0][$key] = $value->summonerId;
		}
		$summonerLeague = $db->getLeagueSummoner($region, $summonerNameIdObj);

		return view("summonerLiveGame")
		->with(['activeGame' => $activeGame])
		->with(['champions' => $staticChampions])
		->with(['summonerSpells' => $summonerSpells])
		->with(['runes' => $staticRunes])
		->with(['summonerLeague' => $summonerLeague])
		->with(['runesPaths' => $staticRunesPaths]);
	}
	public function summonerLiveGameRune(Request $name)
	{

		$summonerName = $name->get("name");
		$id = $name->get("id");

		// Init
		$lol = new LeagueAPI();
		$db = new dbCall();

		// Const?
		$region = 'eun1';
		$locale = 'en_GB';
		$version = '9.17.1';
		$limit = 2;

		$summoner = $db->getSummoner($region, $summonerName);

		clock()->startEvent("getActiveMatchInfo", "getActiveMatchInfo");
		$activeGame = $lol->getActiveMatchInfo($region, $summoner->id);
		clock()->endEvent("getActiveMatchInfo");

		if (!isset($activeGame)) {
			return view('gameIsntLiveAnymore');
		}

		//dump($activeGame);

		clock()->startEvent("getStaticChampions", "getStaticChampions");
		$staticChampions  =  $lol->getStaticChampions(true, $locale, $version);
		clock()->endEvent("getStaticChampions");

		clock()->startEvent("getStaticSummonerSpells", "getStaticSummonerSpells");
		$summonerSpells = $lol->getStaticSummonerSpells($locale, $version);
		clock()->endEvent("getStaticSummonerSpells");

		clock()->startEvent("getStaticRunesReforged", "getStaticRunesReforged");
		$staticRunes = $lol->getStaticRunesReforged($locale, $version);
		clock()->endEvent("getStaticRunesReforged");

		clock()->startEvent("getStaticReforgedRunePaths", "getStaticReforgedRunePaths");
		$staticRunesPaths = $lol->getStaticReforgedRunePaths($locale, $version);
		clock()->endEvent("getStaticReforgedRunePaths");

		foreach ($activeGame->participants as $key => $value) {
			$summonerNameIdObj[0][$key] = $value->summonerId;
		}
		$summonerLeague = $db->getLeagueSummoner($region, $summonerNameIdObj);

		return view('summonerLiveGameRunes')
		->with(['activeGame' => $activeGame])
		->with(['champions' => $staticChampions])
		->with(['summonerSpells' => $summonerSpells])
		->with(['runes' => $staticRunes])
		->with(['summonerLeague' => $summonerLeague])
		->with(['runesPaths' => $staticRunesPaths])
		->with(['key' => $id]);
	}

	public function adminAjax(Request $name)
	{
		

		return view('adminAjax');
	}
}
