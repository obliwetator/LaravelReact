<?php

namespace App\Http\Controllers;

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;
use API\LeagueAPI\Definitions\IPlatform;
use API\LeagueAPI\Definitions\IRegion;
use API\LeagueAPI\Definitions\Platform;
use API\LeagueAPI\Definitions\Region;
use Exception;
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
    
    /** @var string $region */
    public $region;
    
    const   MATCHLIST_GAMES_AMMOUNT = 5,
            TIME_FOR_MATCHLIST = 60 * 5,
            TIME_FOR_SUMMONER = 60 * 60 * 12;

    const   REFRESH_TOO_EARLY = 0,
            MATCHLIST_UP_TO_DATE = 1;
    

    public function __construct(Request $name)
    {
        $this->regions = new Region();
        $this->platforms = new Platform();
        
        if (null !== $name->get("region")) {
            $region = $name->get("region");
            $this->region = $this->regions->getRegionName($region);
        }
        else{
            throw new Exception("region not set");
        }
    }
    private function LeagueAPI()
    {
        if (is_null($this->LeagueAPI)) {
            $this->LeagueAPI = new LeagueAPI([
                LeagueAPI::SET_REGION => $this->region,
            ]);
        }
        
        return $this->LeagueAPI;
    }

    private function LeagueDB()
    {
        if (is_null($this->LeagueDB)) {
            $this->LeagueDB = new dbCall([
                dbCall::SET_REGION => $this->region
            ]);
        }

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

    public function Summoner(Request $request)
	{

        $summonerName = $request->get("name");
        // Temp Limit on how many games we request
        $limit = 2;

		// If no username is entered return 404
		if (!isset($summonerName)) {
			return abort(404);
		}
		$summoner = $this->LeagueDB()->getSummoner($summonerName);

		if (isset($summoner)) {
            $matchlist = $this->LeagueDB()->getMatchlist($summoner->accountId, $limit);
		}
		else{
            return response()->json("Not Found", 404);
        }

        if (isset($matchlist)) {
			$matchById = $this->LeagueDB()->getMatchById($matchlist);
		}
		else{
			$matchById = null;
		}
        return response()->json([
            'summoner' => $summoner,
            'matchlist' => $matchlist->matches,
            'gamesById' => $matchById,
            ]);

    }
    
    public function GetSummonerLeagueTarget(Request $request)
    {
        $summonerId = $request->get("summonerId");
        $summonerLeagueTarget = $this->LeagueDB()->getLeagueSummonerSingle($summonerId);

		return response()->json($summonerLeagueTarget);
    }

    public function getSummonerLiveGame(Request $request)
	{
		$summonerId = $request->get("summonerId");

		$activeGame = $this->LeagueAPI()->getActiveMatchInfo($summonerId);
		// If the summoner is not in a game return a view that says so.
		if (!isset($activeGame)) {
            return response()->json("null");
		}

        return response()->json($activeGame);
    }
    
    public function getLeagues(Request $request)
    {
        $names = $request->get("summoners");
        if (is_null($names)) {
            return 0;
        }

        foreach ($names as $key => $value) {
            $summonerIds[$key] = $value["summonerId"]; 
        }

		$summonerLeague = $this->LeagueDB()->getLeagueSummoner($summonerIds);

        return response()->json($summonerLeague);
    }

    public function lookup(Request $request) 
    {
        // throw new Exception("unimplemented");
        if (count($request->all()) == 3) {
            $name = $request->get("name");
            $region = $request->get("region");
            $championId = $request->get("champion");
            $summoner = $this->LeagueDB()->getSummoner($name);
            $matchlist = $this->LeagueDB()->getMatchlist($summoner->accountId, 90, null, null, 35);
            echo json_encode($summoner);
            die;
        }
        else{
            die("missing parameters");
        }
    }
    public function getMatchlist(Request $request)
    {
        $accountId = $request->get("accountId");
        $lastMatchId = $request->get("lastMatch");
        $summonerRevision = $request->get("summonerRevision");
        $matchlistRevision = $request->get("matchlistRevision");
        
        $now = strtotime(date("Y-m-d H:i:s"));

        // We will always (after some basic time validation) get the current matchlist from the API
        if ($now - strtotime($matchlistRevision) > self::TIME_FOR_MATCHLIST) {
            $apiMatchlist = $this->LeagueAPI()->getMatchlist($accountId, null, null, null, null, null, null, null);
            if ($apiMatchlist == null) {
                return ["code" => null];
            }
            // We go a new matchlist for that summoner. Update the corresponsing field for that summoner
            $this->LeagueDB()->updateUpdateSummonerLastMatchlist($now, $accountId);
        }
        else {
            return ["code" => self::REFRESH_TOO_EARLY];
        }
        // Refresh the summoner after the specified ammount of time has passed
        // Since the summoner information changes very rslowly we can aavoid a lot of api calls
        $summoner = null;
        if ($now - strtotime($summonerRevision) > self::TIME_FOR_SUMMONER) {
            $summoner = $this->LeagueAPI()->getSummonerAccountIdSingle($accountId);
            $this->LeagueDB()->updateSummoner($summoner);
        }

        // If the last game we have in our DB is the same as the current last macth on the client do nothing
        // TODO: Send some error message tht is handled by the client side
        if ($apiMatchlist->matches[0]->gameId == $lastMatchId) {
            // do nothing
            return ["code" => self::MATCHLIST_UP_TO_DATE];
        }
        else {
            $this->LeagueDB()->setMatchlist($apiMatchlist, $accountId);
            // return a part of the array
            $matchlist = array_slice($apiMatchlist->matches, 0, 5);
            // Get 10 games
            for ($i=0; $i < 5; $i++) { 
                $gameIds[$i] = $apiMatchlist->matches[$i]->gameId;
            }
            $gamesById = $this->LeagueAPI()->getMatchById($gameIds);
            $this->LeagueDB()->setMatchById($gamesById);
            $data = ["matchlist" => $matchlist, "gamesById" => $gamesById, "summoner" => $summoner];
            echo json_encode($data);
        }
    }
    // Static Endpoints
    public function getIcons()
    {
        // ob_start('ob_gzhandler');
		$icons = $this->LeagueAPI()->getStaticProfileIcons();
        return response()->json($icons);
    }
    public function getSummonerSpells()
    {
        $summonerSpells = $this->LeagueAPI()->getStaticSummonerSpells();

		return response()->json($summonerSpells);
    }
    public function getChampions()
    {
		$staticChampions = $this->LeagueAPI()->getStaticChampions();

        return response()->json($staticChampions);
    }
    public function getItems()
    {
        // ob_start('ob_gzhandler');

		$staticItems = $this->LeagueAPI()->getStaticItems();
         
        return response()->json($staticItems);
    }
    public function getRunes()
    {
		$staticRunes = $this->LeagueAPI()->getStaticRunesReforged();
        
        return response()->json($staticRunes);
    }
}
