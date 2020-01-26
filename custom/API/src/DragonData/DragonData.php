<?php

namespace API\DragonData;

use API\LeagueAPI\LeagueAPI;
use API\LeagueAPI\Objects\StaticData\StaticRealm;
use League\Flysystem\Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
	SET_ENDPOINT                    = 'datadragon-cdn',
	SET_VERSION                     = 'version';

	const
	STATIC_SUMMONERSPELLS_BY_KEY = "#by-key",
	STATIC_CHAMPION_BY_KEY       = "#by-key";
	
	const CACHE_DIR = __DIR__ ;
	/**
	 *   Contains library settings.
	 * 
	 * @var $settings array
	 */
	static protected $settings = [
		self::SET_ENDPOINT                  => 'https://ddragon.leagueoflegends.com/cdn/'
	];

	/**
	 *   Indicates, whether the library has been initialized or not.
	 *
	 * @var bool $initialized
	 */
	static protected $initialized = false;

	/**
	 *   
	 * @var FilesystemAdapter $cache
	 */
	static protected $cache;

	static protected $staticData = [];
	public static function setCacheInterface(FilesystemAdapter $cache )
	{
		self::$cache = $cache;
	}

	public static function getCacheInterface()
	{
		if (!self::$cache) {
			$cacheInterface = new FilesystemAdapter(
				"DragonCache",
				3600,
				__DIR__
			);
			self::setCacheInterface($cacheInterface);
		}
		return self::$cache;
	}

	protected static function saveStaticData( string $url, array $data )
	{

		$urlHash = md5($url);
		$cache = self::getCacheInterface();
		$staticData = $cache->getItem($urlHash);
		$staticData->set($data);
		// Save the realm data with a lifetime of 1 hour.
		if (strpos($url, "/realms/") !== false) {
			$staticData->expiresAfter(60 * 60);
		}
		else{
			// Store every other static data indefinetly
			// When the realm data updates the static data url will change which will cause it download it again since it doesnt exist
			// It doesnt take up much space and it might be usefull in the future.
			// TODO: Add cronjob to delete very old data ????
			$staticData->expiresAfter(null);
		}
		$cache->save($staticData);
	}

	public static function loadStaticData($url, callable $postprocess = null, bool $data_from_postprocess = false)
	{
		$data = self::loadCachedStaticData($url);
		// Cached Data exists

		if ($data->isHit()) {
			return $data->get();
		}

		$fragmentlessUrl = $url;
		if (($fragmentPos = strpos($url, "#")) !== false)
		{
			$fragmentlessUrl = substr($url, 0, $fragmentPos);
		}

		// Try to load from the web
		$data = @file_get_contents($url);
		if (!$data)
		{
			throw new Exception("Failed to get static data: $url .");
		}

		// Data from web comes as json
		$data = json_decode($data, true);
		self::saveStaticData($fragmentlessUrl, $data);

		if ($postprocess)
		{
			$postprocess_data = $postprocess($fragmentlessUrl, $data);
			if ($data_from_postprocess)
				return $postprocess_data;
		}
		return $data;
	}

	public static function getCacheObject()
	{
		$cache = new FilesystemAdapter(
			"",
			0,
			self::CACHE_DIR
		);
	}
	/** @return \Symfony\Component\Cache\CacheItem */
	public static function loadCachedStaticData($url)
	{
		$urlHash = md5($url);
		$cache = self::getCacheInterface();

		return $cache->getItem($urlHash);
	}
	/** We crate the url in case we need to download the data if ours is outdated. <- TODO: */
	public static function getStaticDataUrl($type, $locale ,$version = null, $suffix = null, $key = null): string
	{
		// If we havent mannually overriden the version use DD version (latest)
		if (is_null($version)) {
			$version = self::$settings[DragonData::SET_VERSION];
		}

		// The key variable is used ONLY for champions folder which contanains individual champion details.
		// Otherwise it will always be null
		// sample url for the champion folder
		//		https://ddragon.leagueoflegends.com/cdn/9.13.1/data/en_GB/champion/Orianna.json
		$url = "https://ddragon.leagueoflegends.com/cdn/$version/data/$locale/$type$key.json$suffix";
		// We add the suffix to differentiate between what array we use

		if (is_null($version)) {
			throw new Exception("No version for DD");
		}


		return $url;
	}

	public static function getStaticChampions( string $locale = 'en_GB', string $version = null, bool $data_by_key ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPIONS, $locale, $version ,$data_by_key ? self::STATIC_CHAMPION_BY_KEY : null);
		return self::loadStaticData($url, [DragonData::class, "_champion"], $data_by_key);
	}

	public static function getStaticChampionDataDetails(string $championId, string $locale = 'enGB', string $version = null) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_CHAMPION, $locale, self::$settings[DragonData::SET_VERSION], null, $championId);
		return self::loadStaticData($url);
	}
	public static function getStaticChampionDataById($championId, $locale, $version = null) : array
	{
		// Grab the whole json
		$data = self::getStaticChampions($locale, self::$settings[DragonData::SET_VERSION]);

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
		$url = self::getStaticDataUrl(self::STATIC_ITEMS, $locale, self::$settings[DragonData::SET_VERSION]);
		return self::loadStaticData($url);
	}
	/** Get a specific item by its ID */
	public static function  getStaticItem($itemId, string $locale = 'en_GB', string $version = null) : array
	{
		// We get all item and return only the specified one;
		$data = self::getStaticItems($locale, self::$settings[DragonData::SET_VERSION]);
		if (isset($data["data"][$itemId]) == false) 
		{
			throw new Exception("Item with ID: $itemId doesn't exist");
		}
		return $data["data"][$itemId];
	}
	
	public static function getStaticProfileIcons(string $locale = 'en_GB', string $version = null ) : array
	{
		$url = self::getStaticDataUrl(self::STATIC_PROFILEICONS, $locale, self::$settings[DragonData::SET_VERSION]);
		return self::loadStaticData($url);
	}

	public static function getStaticRunesReforged(string $locale = 'en_GB', string $version = null) :array
	{
		$url = self::getStaticDataUrl(self::STATIC_RUNESREFORGED, $locale, self::$settings[DragonData::SET_VERSION]);
		return self::loadStaticData($url);
	}

	public static function getStaticSummonerSpells(string $locale = 'en_GB', string $version = null, bool $data_by_key = false ) :array
	{
		$url = self::getStaticDataUrl(self::STATIC_SUMMONERSPELLS, $locale, self::$settings[DragonData::SET_VERSION] ,$data_by_key ? self::STATIC_SUMMONERSPELLS_BY_KEY : null);
		return self::loadStaticData($url, [DragonData::class, "_summoner"], $data_by_key);
	}
	
	public static function getStaticSummonerSpellById(int $key, string $locale = 'en_GB', string $version = null)
	{
		$data = self::getStaticSummonerSpells($locale, self::$settings[DragonData::SET_VERSION], true);

		if (isset($data['data'][$key]) == false) {
			return new Exception("summoner spell not found.");
		}

		return $data['data'][$key];
	}
	public static function getStaticMaps (string $locale = 'en_GB', string $version = null)
	{
		$url = self::getStaticDataUrl(self::STATIC_MAPS, $locale, self::$settings[DragonData::SET_VERSION]);

		return self::loadStaticData($url);
	}

	/**
	 *   Creates new instance by fetching latest Realm info by API static-data endpoint
	 * request.
	 *
	 * @param LeagueAPI $api
	 * @param array $customSettings
	 *
	 * @throws LeagueExceptions\RequestException
	 * @throws LeagueExceptions\ServerException
	 */
	public static function initByApi( LeagueAPI $api)
	{
		self::initByRealmObject($api->getStaticRealm());
	}

	/**
	 *   Creates new instance from Realm object.
	 *
	 * @param StaticRealm $realm
	 * @param array          $customSettings
	 */
	public static function initByRealmObject( StaticRealm $realm)
	{	
		self::$settings[self::SET_ENDPOINT] = $realm->cdn . "/";
		self::$settings[self::SET_VERSION] = $realm->dd;


		self::$initialized = true;
	}

	/**
	 * @param string $region
	 *
	 * @return array
	 * @throws ArgumentException
	 */
	public static function getStaticRealms( string $region ) : array
	{
		$region = strtolower($region);
		$url = "https://ddragon.leagueoflegends.com" . "/realms/$region.json";
		return self::loadStaticData($url);
	}


	// ICONS

	public static function getSummonerSpellsIcons()
	{
		
	}



	// Callable functions
	protected static function _champion( string $url, array $data )
	{
		$url .= self::STATIC_CHAMPION_BY_KEY;
		$data_by_key = $data;
		$data_by_key['data'] = [];

		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});

		self::saveStaticData($url, $data_by_key);
		return $data_by_key;
	}
	protected static function _summoner(string $url, array $data)
	{
		$url .= self::STATIC_SUMMONERSPELLS_BY_KEY;
		$data_by_key = $data;
		$data_by_key['data'] = [];

		array_walk($data['data'], function( $d ) use (&$data_by_key) {
			$data_by_key['data'][(int)$d['key']] = $d;
		});

		self::saveStaticData($url, $data_by_key);
		return $data_by_key;
	}
}
