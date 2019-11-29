<?php

namespace App\Http\Controllers;

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;
use API\DragonData\DragonData;
use API\LeagueAPI\Definitions\Platform;
use API\LeagueAPI\Definitions\Region;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    const 
        GET_ITEMS           = 'items',
        GET_RUNES           = 'runes',
        GET_CHAMPIONS       = 'champions',
        GET_ICONS           = 'icons',
        GET_SUMMONERSPELLS  = 'summonerspells',
        GET_REGION          = 'region';
    const
        SETTINGS_ALLOWED = [
		    self::GET_ITEMS,
		    self::GET_RUNES,
		    self::GET_CHAMPIONS,
		    self::GET_ICONS,
            self::GET_SUMMONERSPELLS,
            self::GET_REGION,
	    ];
    /**
     * @var LeagueAPI $api
     */
    private $LeagueAPI;

    /**
     * @var dbCall $db
     */
    private $LeagueDB;

    /** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;
	

    public function __construct(Request $name)
    {
        if (null !== $name->get("region")) {
            $region = $name->get("region");
            // dd($region);
        }

        $this->regions = new Region();

		$this->platforms = new Platform();	 
    }
    private function LeagueAPI($region)
    {
        $this->LeagueAPI = new LeagueAPI([
            LeagueAPI::SET_REGION => Region::EUROPE_EAST,
        ]);

        return $this->LeagueAPI;
    }

    private function LeagueDB($region)
    {
        $this->LeagueDB = new dbCall([
            dbCall::SET_REGION => Region::EUROPE_EAST
        ]);

        return $this->LeagueDB;
    }

    private function handleRequest(Request $request)
    {
        if (null == $region = $request->get("region")) {
            // No region parameter
            return response()->json("no region set");
        }

        return $region;
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

		clock()->startEvent("GetDbSummoner", "Load Summoner from db");
		$summoner = $this->LeagueDB($region)->getSummoner($region, $summonerName);
		clock()->endEvent("GetDbSummoner");
		if (isset($summoner)) {

		}
		else{
            return response()->json("Not Found", 404);
        }
		return response()->json($summoner);

    }
    
    public function getIcons(Request $request)
    {
        $region = $this->handleRequest($request);

        clock()->startEvent("getStaticProfileIcons", "getStaticProfileIcons");
		$icons = $this->LeagueAPI($region)->getStaticProfileIcons();
        clock()->endEvent("getStaticProfileIcons");
        
        return response()->json($icons);
    }
    public function getSummonerSpells(Request $request)
    {
        $region = $this->handleRequest($request);
        
        clock()->startEvent("getStaticSummonerSpells", "getStaticSummonerSpells");
		$summonerSpells = $this->LeagueAPI($region)->getStaticSummonerSpells();
		clock()->endEvent("getStaticSummonerSpells");

		return response()->json($summonerSpells);
    }
    public function getChampions(Request $request)
    {
        $region = $this->handleRequest($request);

        clock()->startEvent("getStaticChampions", "getStaticChampions");
		$staticChampions  =  $this->LeagueAPI($region)->getStaticChampions(true, );
		clock()->endEvent("getStaticChampions");

        return response()->json($staticChampions);
    }
    public function getItems(Request $request)
    {
        $region = $this->handleRequest($request);

        clock()->startEvent("getStaticItems", "getStaticItems");
		$staticItems = $this->LeagueAPI($region)->getStaticItems();
		clock()->endEvent("getStaticItems");
         
        return response()->json($staticItems);
    }
    public function getRunes(Request $request)
    {
        $region = $this->handleRequest($request);
    
        clock()->startEvent("getStaticRunesReforged", "getStaticRunesReforged");
		$staticRunes = $this->LeagueAPI($region)->getStaticRunesReforged();
		clock()->endEvent("getStaticRunesReforged");
        
        return response()->json($staticRunes);
    }
}
