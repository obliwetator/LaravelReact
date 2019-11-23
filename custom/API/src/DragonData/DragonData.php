<?php

namespace API\DragonData;

use League\Flysystem\Exception;

class DragonData
{
	const
	STATIC_PROFILEICONS     = 'profileicon',
	STATIC_CHAMPIONS        = 'champion',
	STATIC_CHAMPION         = 'champion/',
	STATIC_ITEMS            = 'item',
	STATIC_MASTERIES        = 'mastery',
	STATIC_RUNES            = 'rune',
	STATIC_SUMMONERSPELLS   = 'summoner',
	STATIC_LANGUAGESTRINGS  = 'language',
	STATIC_MAPS             = 'map',
	STATIC_RUNESREFORGED    = 'runesReforged';
	
	const
	STATIC_SUMMONERSPELLS_BY_KEY = "#by-key",
	STATIC_CHAMPION_BY_KEY       = "#by-key";
	
	const CACHE_DIR = __DIR__ . "/cache";


	static protected $staticData = [];

	static protected $version = "9.13.1";

	protected static function saveStaticData( string $urlHash, array $data )
	{
		// Save to file
		@mkdir(self::CACHE_DIR);
		file_put_contents(self::CACHE_DIR . "/$urlHash", serialize($data));
	}

	public static function loadStaticData($url, $processing = null)
	{
		$urlHash = md5($url);
		// try to load the chached data
		$data = self::loadCachedStaticData($url);
		if ($data)
		{
			// Data is cached meaing it's already an array
			return $data;
		}
		// Try to load from the web
		$data = @file_get_contents($url);
		if ($data == false)
		{
			throw new Exception("Failed to get static data: $url .");
		}
		// Data from web comes as json
		$data = json_decode($data, true);
		// save to memory
		self::$staticData["$urlHash"] = $data;
		// save to cache
		self::saveStaticData($urlHash, $data);
		if ($processing)
		{
			self::$processing($url,$data);
		}
		return $data;
	}

	public static function loadCachedStaticData($url)
	{
		$urlHash = md5($url);

		//  First try memory
		if (isset(self::$staticData[$urlHash]))
			return self::$staticData[$urlHash];

		//  Then try file cache
		$data = @file_get_contents(self::CACHE_DIR . "/$urlHash");
		if ($data) {
			return unserialize($data);
		}

		return [];
	}
	/** We crate the url in case we need to download the data if ours is outdated. <- TODO: */
	public static function getStaticDataUrl($type, $locale ,$version = null, $suffix = null, $key = null): string
	{
		// The key variable is used ONLY for champions folder which contanains individual champion details.
		// Otherwise it will always be null
		// sample url for the champion folder
		// ""https://ddragon.leagueoflegends.com/cdn/9.13.1/data/en_GB/champion/Orianna.json""
		$url = "https://ddragon.leagueoflegends.com/cdn/$version/data/$locale/$type$key.json$suffix";
		// We add the suffix to differentiate between what array we use

		return $url;
	}

	public static function getStaticChampions( string $locale = 'en_GB', string $version = null ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPIONS, $locale, $version);
		return self::loadStaticData($url, "_champion");
	}

	public static function getStaticChampionDataSimple(string $locale = 'en_GB', string $version = null) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPIONS, $locale, self::$version, self::STATIC_CHAMPION_BY_KEY);

		// We try to load the data from the cache first
		$data = self::loadCachedStaticData($url);
		// If we don't have it in cache(newer version etc.). We will grab into from the web and store it in the cache
		if (!$data)
		{
			self::getStaticChampions($locale, $version);
			$data = self::loadCachedStaticData($url);
		}

		return $data;
	}

	public static function getStaticChampionsWithKeys(string $locale = 'en_GB', string $version = null ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPIONS,$locale, $version, self::STATIC_CHAMPION_BY_KEY);

		$data = self::loadCachedStaticData($url);
		if (!$data)
		{
			self::getStaticChampions($locale, $version);
			$data = self::loadCachedStaticData($url);
		}
		return $data;
	}
	public static function getStaticChampionDataDetails(string $championId, string $locale = 'enGB', string $version = null) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPION, $locale, $version, null, $championId);
		return self::loadStaticData($url);
	}
	public static function getStaticChampionDataById($championId, $locale, $version = null) : array
	{
		// Grab the whole json
		$data = self::getStaticChampionDataSimple($locale, $version = "9.13.1");

		if (isset($data["data"][$championId]) == false) 
		{
			throw new Exception("Champion with ID: $championId doesn't exist");
			return [];
		}
		// We return only the data for the specified champion
		return $data["data"][$championId];
	}
	/** Get ALL items */
	public static function getStaticItems(string $locale = 'en_GB', string $version = null ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_ITEMS, $locale, self::$version);
		return self::loadStaticData($url);
	}
	/** Get a specific item by its ID */
	public static function  getStaticItem($itemId, string $locale = 'en_GB', string $version = null) : array
	{
		// We get all item and return only the specified one;
		$data = self::getStaticItems($locale, $version);
		if (isset($data["data"][$itemId]) == false) 
		{
			throw new Exception("Item with ID: $itemId doesn't exist");
		}
		return $data["data"][$itemId];
	}
	
	public static function getStaticProfileIcons(string $locale = 'en_GB', string $version = null ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_PROFILEICONS, $locale, $version);
		return self::loadStaticData($url);
	}

	public static function getStaticRunesReforged(string $locale = 'en_GB', string $version = null) :array
	{
		$url = self::getStaticDataUrl(self::STATIC_RUNESREFORGED, $locale, $version);
		return self::loadStaticData($url);
	}

	public static function getStaticSummonerSpells(string $locale = 'en_GB', string $version = null) :array
	{
		$url = self::getStaticDataUrl(self::STATIC_SUMMONERSPELLS, $locale, $version);
		
		return self::loadStaticData($url,"_summoner");
	}
	
	public static function getStaticSummonerSpellsWithKeys(string $locale = 'en_GB', string $version = null)
	{
		$url = self::getStaticDataUrl(self::STATIC_SUMMONERSPELLS, $locale, $version, self::STATIC_SUMMONERSPELLS_BY_KEY);
		$data = self::loadCachedStaticData($url);
		if (!$data)
		{
			self::getStaticSummonerSpells($locale, $version);
			$data = self::loadCachedStaticData($url);
		}
		return $data;
	}

	public static function getStaticSummonerSpellById(int $key, string $locale = 'en_GB', string $version = null)
	{
		$data = self::getStaticSummonerSpellsWithKeys($locale, $version);

		if (isset($data['data'][$key]) == false) {
			return new Exception("summoner spell not found.");
		}

		return $data['data'][$key];
	}
	public static function getStaticMaps (string $locale = 'en_GB', string $version = null)
	{
		$url = self::getStaticDataUrl(self::STATIC_MAPS, $locale, $version);

		return self::loadStaticData($url);
	}
	protected static function _champion( string $url, array $data )
	{
		$url = $url . self::STATIC_CHAMPION_BY_KEY;
		$urlHash = md5($url);

		$data_by_key = $data;
		$data_by_key['data'] = [];

		// black magic. Converts the $data["data"][champion name] to $data["data"][$championId]
		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});
		// Save the converted array to cache,e
		self::saveStaticData($urlHash, $data_by_key);
		// save to memory
		self::$staticData["$urlHash"] = $data_by_key;
	}
	protected static function _summoner(string $url, array $data)
	{
		$url = $url . self::STATIC_SUMMONERSPELLS_BY_KEY;

		$urlHash = md5($url);
		$data_by_key = $data;
		$data_by_key['data'] = [];

		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});
		self::saveStaticData($urlHash, $data_by_key);

		self::$staticData[$urlHash] = $data_by_key;
	}
}
