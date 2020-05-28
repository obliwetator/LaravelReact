<?php

namespace API\LeagueAPI;

use API\DragonData\DragonData;
use API\LeagueAPI\Definitions\IPlatform;
use API\LeagueAPI\Definitions\IRegion;
use API\LeagueAPI\Definitions\Platform;
use API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\StaticData\StaticRealm;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use API\LeagueAPI\Definitions\Region;



require 'curl.php';
require 'functions.php';

class LeagueAPI
{
	/**
	 * Settings constants.
	 */
	const
		SET_REGION                   = 'SET_REGION',
		SET_API_BASEURL              = 'SET_API_BASEURL';

		const
		//  List of required setting keys
		SETTINGS_REQUIRED = [
			self::SET_REGION,
		],
		//  List of allowed setting keys
		SETTINGS_ALLOWED = [
			self::SET_REGION,
			self::SET_API_BASEURL,
		],
		SETTINGS_INIT_ONLY = [
		];

	const CACHE_DIR = __DIR__ . "/cache";
	/**
	 *   Contains current settings.
	 *
	 * @var array $settings
	 */
	protected $settings = array(
		self::SET_API_BASEURL      => '.api.riotgames.com',
	);

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;

	/** @var string $platforms */
	public $region;
	

	// $assoc determined whether the array is converted to an object(false) or an assosiative array(true)
	private $assoc = true;

	public function __construct( array $settings )
	{
		//  Assigns allowed settings
		foreach (self::SETTINGS_ALLOWED as $key)
		if (isset($settings[$key]))
			$this->settings[$key] = $settings[$key];

		$this->regions = new Region();

		$this->platforms = new Platform();	

		$this->region = $this->platforms->getPlatformName($this->settings[LeagueAPI::SET_REGION]);

		DragonData::initByApi($this);

		// TODO Setup caching for static files from DD
		$DragonDataCache = $this->setUpDragonDataCache();

		DragonData::setCacheInterface($DragonDataCache);

	}

	private function setUpDragonDataCache(){
		// Create cache
		$cache = new FilesystemAdapter(

			// a string used as the subdirectory of the root cache directory, where cache
			// items will be stored
			$namespace = 'DragonDataCache',
		
			// the default lifetime (in seconds) for cache items that do not define their
			// own lifetime, with a value 0 causing items to be stored indefinitely (i.e.
			// until the files are deleted)
			$defaultLifetime = 0,
		
			// the main cache directory (the application needs read-write permissions on it)
			// if none is specified, a directory is created inside the system temporary directory
			$directory = self::CACHE_DIR
		);

		return $cache;
	}

	// API CALLS
	/** @return Objects\Summoner[] */
	public function getSummonerName(string $region,array $summonerName)
	{	
		foreach ($summonerName as $key => $summonerN) {
			$summonerN = str_replace(' ', '', $summonerN);
			$targetUrls[$key] = "https://{$region}.api.riotgames.com/lol/summoner/v4/summoners/by-account/{$summonerN}";
		}

		$data = multiCurl($this->region, $targetUrls, $this->assoc);
		foreach ($data as $key => $value) {

			$summoner[$key] = new Objects\Summoner($value);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner[$key]->trimmedName = str_replace(' ', '', $summoner[$key]->name);
		}

		return $summoner;
	}

	private function setPlatform($region)
	{
		$platform = $this->platforms->getPlatformName($region);

		return $platform;
	}
	/** @param string  $summonerName */
	public function getSummonerNameSingle($summonerName)
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/by-name/{$summonerName}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		if (isset($data)) {
			$summoner = new Objects\Summoner($data);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner->trimmedName = str_replace(' ', '', $summoner->name);
	
			return $summoner;
		}
		else{
			return null;
		}
	}

	public function getSummonerId(array $summonerId)
	{
		foreach ($summonerId as $key => $summonerN) {
			$summonerN = str_replace(' ', '', $summonerN);
			$targetUrls[$key] = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/by-name/{$summonerN}";
		}

		$data = multiCurl($this->region, $targetUrls, $this->assoc);
		foreach ($data as $key => $value) {

			$summoner[$key] = new Objects\Summoner($value);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner[$key]->trimmedName = str_replace(' ', '', $summoner[$key]->name);
		}

		return $summoner;
	}

	public function getSummonerPuuId(array $puuId)
	{
		foreach ($puuId as $key => $summonerN) {
			$summonerN = str_replace(' ', '', $summonerN);
			$targetUrls[$key] = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/{$summonerN}";
		}

		$data = multiCurl($this->region, $targetUrls, $this->assoc);
		foreach ($data as $key => $value) {

			$summoner[$key] = new Objects\Summoner($value);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner[$key]->trimmedName = str_replace(' ', '', $summoner[$key]->name);
		}

		return $summoner;
	}

	public function getSummonerAccountId(array $summonerAccountId)
	{
		foreach ($summonerAccountId as $key => $summonerN) {
			$summonerN = str_replace(' ', '', $summonerN);
			$targetUrls[$key] = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/by-account/{$summonerN}";
		}

		$data = multiCurl($this->region, $targetUrls, $this->assoc);
		foreach ($data as $key => $value) {

			$summoner[$key] = new Objects\Summoner($value);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner[$key]->trimmedName = str_replace(' ', '', $summoner[$key]->name);
		}

		return $summoner;
	}

	public function getSummonerAccountIdSingle(string $summonerAccountId)
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/by-account/{$summonerAccountId}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		if (isset($data)) {
			$summoner = new Objects\Summoner($data);
			// Remove Spaces and save name with proper capitalization
			// $summoner->nameInputSanitization($summoner->name);
			$summoner->trimmedName = str_replace(' ', '', $summoner->name);
	
			return $summoner;
		}
		else{
			return null;
		}
	}

	public function getMatchlist(string $accountId, int $queue = null, int $season = null, int $champion = null, int $beginTime = null, int $endTime = null, int $beginIndex = null, int $endIndex = null)
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/match/v4/matchlists/by-account/{$accountId}";

		$additionalParameters['queue'] = $queue;
		$additionalParameters['season'] = $season;
		$additionalParameters['champion'] = $champion;
		$additionalParameters['beginTime'] = $beginTime;
		$additionalParameters['endTime'] = $endTime;
		$additionalParameters['beginIndex'] = $beginIndex;
		$additionalParameters['endIndex'] = $endIndex;

		$data = curl($this->region, $targetUrl, $this->assoc, $additionalParameters);
		if (isset($data)) {
			return new Objects\MatchList($data);
		}
		else{
			return null;
		}

	}
	/** @var Objects\MatchById[] $matchById
	 * 	@return Objects\MatchById[]
	 */
	public function getMatchById(array $matchIds): array
	{		
		foreach ($matchIds as $key => $matchId) {
            $targetUrl[$key] = "https://{$this->region}.api.riotgames.com/lol/match/v4/matches/{$matchId}";
		}

		$data = multiCurl($this->region, $targetUrl, $this->assoc);

		foreach ($data as $key => $value) {
			$matchById[$key] = new Objects\MatchById($value);
		}
		return $matchById;
	}

	public function getMatchTimeline(string $region, int $matchId): Objects\matchTimeline
	{
		$targetUrl = "https://{$region}.api.riotgames.com/lol/match/v4/timelines/by-match/{$matchId}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\matchTimeline($data);
	}
	/** Can return NULL */
	public function getActiveMatchInfo( string $summonerId)
	{
		//throw new Exception("Not implemented");
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/{$summonerId}";

		$data = curl($this->region,$targetUrl, $this->assoc);

		if (isset($data)) {
			return new Objects\activeGame($data);
		}
		else{
            return null;
        }
	}

	public function getChampionMasteriesSummoner(string $region, string $summonerId): Objects\championMasteriesFrame
	{
		$targetUrl = "https://{$region}.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/{$summonerId}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\championMasteriesFrame($data);
	}

	public function getChampionMasteriesSummonerByChampion(string $summonerId, int $championId): Objects\championMasteriesByChampion
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/{$summonerId}/by-champion/{$championId}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\championMasteriesByChampion($data);
	}

	/** Returns the sum of all mastery levels added up. Single int */
	public function getChampionMasteriesScore(string $summonerId): int
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/champion-mastery/v4/scores/by-summoner/{$summonerId}";

		return curl($this->region, $targetUrl, $this->assoc);
	}

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 * 
	 * THIS FUNCTION CAN RETURN NULL.
	 *
	 *  @param mixed $region
	 * 	@param Objects\Summoner[][] $summoners
	 *  @return Objects\LeagueSummoner[][] */
	public function getLeagueSummoner(array $summoners)
	{

		foreach ($summoners as $key => $summoner) {
			$targetUrls[$key] = "https://{$this->region}.api.riotgames.com/lol/league/v4/entries/by-summoner/{$summoner}";
		}
		$data = multiCurl($this->region, $targetUrls, $this->assoc);
		

		foreach ($data as $key => $value) {
			foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if ($value3 === false || $value3 === true) {
                        // we convert the true/false to an int
                        $data[$key][$key2][$key3] = (int)$value3;
                    }
                }
			}
		}

		if (isset($data)) {
			foreach ($data as $key => $value) {
				if (empty($value)) {
					$obj[$key] = null;
				}
				else{
					foreach ($value as $key2 => $value2) {
						// Name the array values after their corresponsing league to find the easier
						$obj[$key][$value2["queueType"]] = new Objects\LeagueSummoner($value2);
					}
				}
				
			}
		}
		if (isset($obj)) {
			return $obj;
		} else {
			return $obj = null;
		}

	}	

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 * 
	 * 	THIS FUNCTION CAN RETURN NULL.
	 *
	 *  @param mixed $region
	 * 	@param Objects\Summoner[][] $summoners
	 *  @return Objects\LeagueSummoner[][] */
	public function getLeagueSummonerSingle(string $summoner)
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/league/v4/entries/by-summoner/{$summoner}";

		$data = curl($this->region,$targetUrl, $this->assoc);

		if (empty($data)) {
			// Returns [] (empty array)
			return $data;
		}
		else{
			foreach ($data as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if ($value2 === false || $value2 === true) {
						// we convert the true/false to an int
						$data[$key][$key2] = (int)$value2;
					}
				}
				$entry[$value["queueType"]] = new Objects\LeagueSummoner($data[$key]);
			}
			return $entry;
		}

	}
	

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 *
	 * @param mixed $region
	 */
	public function getChallengerLeagues(string $gameMode): Objects\ChallengerLeagues
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/league/v4/challengerleagues/by-queue/{$gameMode}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\ChallengerLeagues($data);
	}

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 *
	 * @param string $region
	 */
	public function getGrandmasterLeagues(string $gameMode): Objects\ChallengerLeagues
	{
		$targetUrl = "https://{$this->region}.api.riotgames.com/lol/league/v4/grandmasterleagues/by-queue/{$gameMode}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\ChallengerLeagues($data);
	}

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 *
	 * @param string $region
	 */
	public function getMasterLeague(string $region, string $gameMode): Objects\ChallengerLeagues
	{
		$targetUrl = "https://{$region}.api.riotgames.com/lol/league/v4/masterleagues/by-queue/{$gameMode}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\ChallengerLeagues($data);
	}

	/** Valid Game Modes
	 *  RANKED_SOLO_5x5,
	 *  RANKED_TFT,
	 *  RANKED_FLEX_SR,
	 *  RANKED_FLEX_TT.
	 *
	 * This function can return null
	 * @param string $region
	 *
	 * @return Objects\LeagueEntries[] */
	public function getLeagueEntries(string $region, string $gameMode, string $division, string $tier, int $page = null)
	{
		$targetUrl = "https://{$region}.api.riotgames.com/lol/league/v4/entries/{$gameMode}/{$division}/{$tier}";

		$additionalParameters['page'] = $page;
		$data = curl($this->region, $targetUrl, $this->assoc, $additionalParameters);

		if (isset($data)) {
			foreach ($data as $key => $value) {
				$obj[$key] = new Objects\LeagueEntries($value);
			}
			if (isset($obj)) {
				return $obj;
			} else {
				return $obj = null;
			}
		}
	}

	public function getLeagueById(string $region, string $leagueId): Objects\ChallengerLeagues
	{
		$targetUrl = "https://{$region}.api.riotgames.com/lol/league/v4/leagues/{$leagueId}";

		$data = curl($this->region, $targetUrl, $this->assoc);

		return new Objects\ChallengerLeagues($data);
	}

	// STATIC DATA CALLS
	public function getStaticChampions(bool $data_by_key = true, string $locale = 'en_GB', string $version = null): StaticData\StaticChampionList
	{
		$data = DragonData::getStaticChampions($locale, $version, $data_by_key);

		// Create missing data
		$data['keys'] = array_map(function ($d) use ($data_by_key) {
			return $data_by_key
				? $d['id']
				: $d['key'];
		}, $data['data']);
		$data['keys'] = array_flip($data['keys']);

		return new StaticData\StaticChampionList($data);
	}

	public function getStaticChampion($championId, $details = false, $locale = 'en_GB', $version = '9.13.1'): StaticData\StaticChampion
	{
		// $version/data/$locale/champion.json
		// champion.json (less detailed version)
		// We grab the whole json(as an array) and then we will search for the specified champion ID and return the appropriate array
		$data = DragonData::getStaticChampionDataById($championId, $locale, $version);
		if (true == $details) {
			// We have the champion data. With the champion name we grab the detailed champion file
			// $version/data/$locale/champion/$championId(Champion Name)
			// "https://ddragon.leagueoflegends.com/cdn/9.13.1/data/en_US/champion/Orianna.json"
			// The brackets on the end return the proper array.
			$data = DragonData::getStaticChampionDataDetails($data['id'], $locale, $version)['data'][$data['id']];
		}

		return new StaticData\StaticChampion($data);
	}

	/** Get ALL items */
	public function getStaticItems(string $locale = 'en_GB', string $version = null): StaticData\StaticItemList
	{

		$data = DragonData::getStaticItems($locale, $version);

		array_walk($data['data'], function (&$d, $k) {
			$d['id'] = $k;
		});

		return new StaticData\StaticItemList($data);
	}

	/** Get a specific item by its ID */
	public function getStaticItem(int $itemId, string $locale = 'en_GB', string $version = null): StaticData\StaticItem
	{
		$data = DragonData::getStaticItem($itemId, $locale, $version);

		$data['id'] = $itemId;

		return new StaticData\StaticItem($data);
	}

	public function getStaticProfileIcons(string $locale = 'en_GB', string $version = null): StaticData\StaticProfileIconData
	{
		$data = DragonData::getStaticProfileIcons($locale, $version);
		return new StaticData\StaticProfileIconData($data);
	}

	/** Retrieve reforged rune path.
	 *
	 * This will retrieve all the runes and they ARE organized by the Paths ( 8100, 8200 .. etc)
	 */
	public function getStaticReforgedRunePaths(string $locale = 'en_US', string $version = null): StaticData\StaticRunesReforgedPathList
	{
		// Fetch StaticData from JSON files
		$data = DragonData::getStaticRunesReforged($locale, $version);

		// Create missing data
		$r = [];
		foreach ($data as $path)
			$r[$path['id']] = $path;
		$data = ['paths' => $r];

		// Parse array and create instances
		return new StaticData\StaticRunesReforgedPathList($data);
	}
	/** Retrieve reforged rune path.
	 *
	 * This will retrieve all the runes and they AREN'T organized by the Paths
	 */
	public function getStaticRunesReforged(string $locale = 'en_GB', string $version = null)
	{
		$data = DragonData::getStaticRunesReforged($locale, $version);

		$r = [];
		foreach ($data as $path) {
			$r[$path['id']] = [
				'key' => $path['key'], 
				'icon' => $path['icon'], 
				'name' => $path['name'] 
				];

			foreach ($path['slots'] as $slot) {
				foreach ($slot['runes'] as $item) {
					$r[$item['id']] = $item;
				}
			}
		}
		// $data = ['runes' => $r];
		return $r;
		// return new StaticData\StaticRunesReforgedList($data);
	}
	/** Get all summoner spells
	 *
	 * $key value determines whether we get an array that indexes the Summoner Spells by their ID or their name. Default is ID
	 */
	public function getStaticSummonerSpells(bool $data_by_key = true ,string $locale = 'en_GB', string $version = null) : StaticData\StaticSummonerSpellList
	{
		$data = DragonData::getStaticSummonerSpells($locale, $version, $data_by_key);
		return new StaticData\StaticSummonerSpellList($data);
	}

	public function getStaticSummonerSpell(int $key, string $locale = 'en_GB', string $version = null)
	{
		$data = DragonData::getStaticSummonerSpellById($key, $locale, $version);

		return new StaticData\StaticSummonerSpell($data);
	}

	/** This will return ONLY the current active maps.*/
	public function getStaticMaps(string $locale = 'en_GB', string $version = null)
	{
		return DragonData::getStaticMaps($locale, $version);
	}

	protected function addQuery(string $name, $value)
	{
		if (!is_null($value)) {
			$this->query_data[$name] = $value;
		}

		return $this;
	}

		/**
	 *   Retrieve realm data. (Region versions)
	 *
	 * @cli-name get-realm
	 * @cli-namespace static-data
	 *
	 * @return StaticRealm
	 * @throws RequestException
	 * @throws ServerException
	 */
    public function getStaticRealm(): array
    {
		$result = false;
		// Fetch StaticData from JSON files
		$result = DragonData::getStaticVersion();

		$this->result_data = $result;

		// return the 2 latest version
		return array_slice($result, 0, 2);
    }
}
