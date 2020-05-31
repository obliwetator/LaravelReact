<?php

namespace API\LeagueDB;

use API\LeagueAPI\Definitions\IPlatform;
use API\LeagueAPI\Definitions\IRegion;
use API\LeagueAPI\Definitions\Platform;
use API\LeagueAPI\Definitions\Region;
use API\LeagueAPI\LeagueAPI;
use API\LeagueAPI\Objects;
use Exception;

require_once("dbConnect.php");



class LeagueDB
{
	/** @var \mysqli $conn */
	public $conn;

	/** @var IRegion $regions */
	public $regions;

	/** @var IPlatform $platforms */
	public $platforms;

	/** @var string $region
	 * eun1 etc...
	 */
	public $region;

	/** @var string $region
	 * workaround
	 */
	public $platform;
	
	/**
	 * Settings constants.
	 */
	const
		SET_REGION                   = 'SET_REGION',
		SET_PLATFORM                 = 'SET_PLATFORM',
		SET_API_BASEURL              = 'SET_API_BASEURL';

	const
		//  List of required setting keys
		SETTINGS_REQUIRED = [
			self::SET_REGION,
		],
		//  List of allowed setting keys
		SETTINGS_ALLOWED = [
			self::SET_REGION,
			self::SET_PLATFORM,
			self::SET_API_BASEURL,
		],
		SETTINGS_INIT_ONLY = [
		];
	const 
		SUMMONER_REFRESH = 60 * 60 * 24, // 1 day
		MATCHLIST_REFRESH = 60 * 60; //  

	/**
	 *   Contains current settings.
	 *
	 * @var array $settings
	 */
	protected $settings = array(
		self::SET_API_BASEURL      => '.api.riotgames.com',
		self::SET_REGION           => 'SET_REGION',
	);

	public function __construct( array $settings )
	{
		//  Assigns allowed settings
		foreach (self::SETTINGS_ALLOWED as $key)
		if (isset($settings[$key]))
			$this->settings[$key] = $settings[$key];

		$this->regions = new Region();
		
		$this->platforms = new Platform();

		// If set region is not isset
		if ($this->settings[self::SET_REGION] !== 'SET_REGION') {
			$this->region = $this->setPlatform($this->settings[self::SET_REGION]);
		}
		else{
			$this->region = $this->settings[self::SET_PLATFORM];
		}


	}
	private function openCon()
	{
		$this->conn = DbOpenConn($this->region);
	}
	private function closeCon()
	{
		$this->conn->close();
	}

	private function makeDbCallGet(string $query)
	{
		$this->openCon();

		// Normally all the data rerived is converted as strigs. This will cause the values to be returned as their native datatype
		$this->conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

		$queryResult = $this->conn->query($query);
		if ($queryResult == true) {
			
			if ($queryResult->num_rows > 1) {
				for ($i = 0; $i < $queryResult->num_rows; $i++) {
					$resultAssoc[$i] = $queryResult->fetch_assoc();
				}
			} elseif ($queryResult->num_rows == 0) {
				$this->closeCon($this->conn);

				return null;
			} else {
				$resultAssoc = $queryResult->fetch_assoc();
			}
		} else {
			echo($query);
			throw new Exception("something went wrong with the SQL query GET. " . $this->conn->error);
		}
		$this->closeCon($this->conn);
		return $resultAssoc;
	}

	private function makeDbCallGetMulti(string $query)
	{	
		$this->openCon($this->region);
		$matches = [];
		$x = 0;
		if ($this->conn->multi_query($query)) {
			do {
				/* store first result set */
				if ($result = $this->conn->store_result(null)) {
					if ($result->num_rows > 1) {
						for ($i=0; $i < $result->num_rows; $i++) { 
							$matches[$x][$i] = $result->fetch_assoc();
						}
					}
					elseif ($result->num_rows == 1) {
						$matches[$x] = $result->fetch_assoc();
					} else {
						$matches[$x] = null;
					}
					$x++;
					$result->free();
				}

				if ($this->conn->more_results()) {
					// DO something between results

				} else {
					break;
				}
			} while ($this->conn->next_result());
		}
		$this->closeCon($this->conn);
		return $matches;
	}

	private function makeDbCallGetMultiLeague(string $query)
	{
		$this->openCon();
		$matches = [];
		$x = 0;
		if ($this->conn->multi_query($query)) {
			do {
				/* store first result set */
				if ($result = $this->conn->store_result(null)) {
					if ($result->num_rows) {
						for ($i=0; $i < $result->num_rows; $i++) { 
							$matches[$x][$i] = $result->fetch_assoc();
						}
					} else {
						$matches[$x] = null;
					}
					$x++;
					$result->free();
				}

				if ($this->conn->more_results()) {
					// DO something between results

				} else {
					break;
				}
			} while ($this->conn->next_result());
		}
		$this->closeCon($this->conn);
		return $matches;
	}

	private function makeDbCallSet(string $query)
	{
		$this->openCon();

		$queryResult = $this->conn->query($query);
		if ($queryResult == true) {
			$mySqliWarning = $this->conn->get_warnings();

			if ($mySqliWarning == true) {
				if ($mySqliWarning->errno == 1062) {
					// We will get this error this we will use INSERT IGNORE wich will throw a wrning on duplicate primary key
				} else {
					eh("Mysqli error  $mySqliWarning->errno  $mySqliWarning->message ");
				}
			}
		} else {
			// There is something wrong with the query
			// TODO: Log error messages?
			throw new Exception("something went wrong with the SQL query SET. " . $this->conn->error);
		}

		$this->closeCon($this->conn);
	}

	private function makeDbCallSetMulti(string $region, string $query)
	{
		
	}
	/** @return Objects\Summoner
	 * 
	 */
	public function getSummoner(string $summonerName)
	{
		$summonerName = str_replace(' ', '', $summonerName);
		
		$selectQuery = "SELECT * FROM `summoner_$this->region` WHERE `trimmedName` = '$summonerName'";
		$resultAssoc = $this->makeDbCallGet($selectQuery);

		// First time we lookup. If it doesn't exist make an API request and put it in the DB.
		if ($resultAssoc == null) {
			$lol = $this->makeApiRequest();
			$summonerDataDb = $lol->getSummonerNameSingle($summonerName);

			if (isset($summonerDataDb)) {
				$this->setSummonerSingle($summonerDataDb);
			}
		} else {
			$summonerDataDb = new Objects\Summoner($resultAssoc);
		}
		return $summonerDataDb;
	}
	public function getSummoners(string $region, array $summonerNames)
	{
		$selectQuery = "";
		foreach ($summonerNames as $key => $summoner) {
			$summoner = str_replace(' ', '', $summoner);

			$selectQuery .= "SELECT * FROM `summoner_$region` WHERE `accountId` = '$summoner';";
		}
		$resultDb = $this->makeDbCallGetMulti($selectQuery);


		// First time we lookup. If it doesn't exist make an API request and put it in the DB.
        foreach ($resultDb as $key => $summoner) {
            if ($resultDb[$key] == null) {
                $summonerToFind[$key] = $summonerNames[$key];
			}
			
            if (isset($summonerToFind)) {
				$lol = $this->makeApiRequest();

				$result = $lol->getSummonerName($region, $summonerToFind);
                $this->setSummoner($region, $result);
            } else {

            }
		}
		if (isset($result)) {
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
			// Then we sort those 2 arrays by their key
			ksort($result);
		}
		else{
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
		}
		// Convert to MatchById Class
		foreach ($result as $key => $summoner) {
			if (is_object($summoner)) {
				$SummonerData[$key] = $result[$key];
			} else {
				$SummonerData[$key] = new Objects\Summoner($summoner);

			}
		}
		return $SummonerData;
	}
	/** @param Objects\Summoner[] $summoner */
	public function setSummoner(string $region, $summoners)
	{
		$insertQuery = "INSERT IGNORE INTO `summoner_$region`(`id`, `accountId`, `puuid`, `name`, `profileIconId`, `revisionDate`, `summonerLevel`, `trimmedName`)VALUES ";
		foreach ($summoners as $key => $value) {
			if (array_key_last($summoners) == $key) {

				$insertQuery .= "('$value->id','$value->accountId','$value->puuid','$value->name','$value->profileIconId','$value->revisionDate','$value->summonerLevel','$value->trimmedName');";
			}
			else{
				$insertQuery .= "('$value->id','$value->accountId','$value->puuid','$value->name','$value->profileIconId','$value->revisionDate','$value->summonerLevel','$value->trimmedName'),";
			}
		}

		

		$this->makeDbCallSet($insertQuery);
	}

	public function setSummonerSingle($summoner)
	{
		$insertQuery = "INSERT IGNORE INTO `summoner_$this->region`(`id`, `accountId`, `puuid`, `name`, `profileIconId`, `revisionDate`, `summonerLevel`, `trimmedName`)
		VALUES ('$summoner->id','$summoner->accountId','$summoner->puuid','$summoner->name','$summoner->profileIconId','$summoner->revisionDate','$summoner->summonerLevel','$summoner->trimmedName');";

		$this->makeDbCallSet($insertQuery);
	}
	/** @return null|Objects\MatchList */
	public function getMatchlist(string $accountId, int $limit, $queue = null, $season = null, $champion = null, $beginTime = null, $endTime = null, $beginIndex = null)
	{
		$selectQuery = "SELECT * FROM `matchlist_$this->region` WHERE `accountId` = '$accountId' ORDER BY `matchlist_$this->region`.`timestamp` DESC LIMIT $limit";
		$resultAssoc = $this->makeDbCallGet($selectQuery);
		// No matchlist in DB
		if ($resultAssoc == null)
		{
			$lol = $this->makeApiRequest();
			$dbMatchlist = $lol->getMatchlist($accountId, $queue, $season, $champion, $beginTime, $endTime, $beginIndex, $limit);
			if (isset($dbMatchlist)) {
				$this->setMatchlist($dbMatchlist, $accountId);
			}
			return $dbMatchlist;
		}
		else {
			// The requested ammount of games dont match the games we have in our DB. Try to get them
			if (sizeof($resultAssoc) < $limit) {
				$lol = $this->makeApiRequest();
				$dbMatchlist = $lol->getMatchlist($accountId, $queue, $season, $champion, $beginTime, $endTime, $beginIndex, $limit);
				if (isset($dbMatchlist)) {
					$this->setMatchlist($dbMatchlist, $accountId);
				}
			}
			else{
				$result["matches"] = $resultAssoc;
				$dbMatchlist = new Objects\MatchList($result);
			}

			return $dbMatchlist;
		}
	}

	public function setMatchlist(Objects\MatchList $getMatchlist, string $accountId)
	{
		$value = "";
		$selectQuery2 = "";
		// We check which of those games we have in our DB for that spacific summoner
		// If the result returned is null that means that gameId is not in our db
		// In this case we should get the game from the API
		for ($i = 0; $i < sizeof($getMatchlist->matches); $i++) {
			$matches = $getMatchlist->matches[$i];
			$selectQuery2 .= "SELECT * FROM `matchlist_$this->region` WHERE `gameId` = '$matches->gameId' AND `accountId` = '$accountId';";
		}
		// Returns an array with the format $dbMatchlist[]["propertyName"]
		$dbMatchlist = $this->makeDbCallGetMulti($selectQuery2);
		// We get the game that matches the gameIds in our DB and check them up against the API data to decide which games we will store in our DB
			for ($i = 0; $i < sizeof($dbMatchlist); $i++) {
				// We will create the sql query if we DONT have the game in our db
				// If we dont have the game in our DB the value of dbMatchlist array at that index will be NULL
				// We dont have the game in our DB. We will create the query
				if ($dbMatchlist[$i] == null) {
					// Makes the construction of the query easier
					$match = $getMatchlist->matches[$i];
					// If its the last element of the list we add an ;
					if (sizeof($dbMatchlist) == $i + 1) {
						$value .= "('$accountId', '$match->gameId', '$match->platformId', '$match->champion', '$match->queue', '$match->season', '$match->timestamp', '$match->role', '$match->lane');";
					} else {
						$value .= "('$accountId', '$match->gameId', '$match->platformId', '$match->champion', '$match->queue', '$match->season', '$match->timestamp', '$match->role', '$match->lane'),";
					}
				} else {
					// TODO More efficient solution?
					break;
				}
			}
			if (substr($value, -1) == ',') {
				$value = substr_replace($value, ';', -1);
			}
			// We initialize the $value with "" so if default present is empty it means that the value isn't set
		if ($value != "") {
			$insertQuery = "INSERT INTO `matchlist_$this->region`(`accountId`, `gameId`,`platformId`,`champion`,`queue`, `season`, `timestamp`, `role`, `lane`) VALUES $value";
			$this->makeDbCallSet($insertQuery);
		} else {
			// We have nothing to add to DB which means we do nothing
		}
	}

	/** Retuns an array of Matches
	 * @var Objects\MatchById[] $matchById
	 * @return Objects\MatchById[]*/
	public function getMatchById(Objects\MatchList $matchlist)
	{
		$selectQuery = "";
		foreach ($matchlist->matches as $key => $value) {
			$matchIds[$key] = $matchlist->matches[$key]->gameId;
			$selectQuery = $selectQuery . "SELECT * FROM `gamebyid_$this->region` WHERE `gameId` = " . $matchIds[$key] . " ORDER BY `gameId` DESC;\n";
		}
		$resultDb = $this->makeDbCallGetMulti($selectQuery);

		// Find Missing games
		foreach ($resultDb as $key => $value) {
			if (isset($value)) {
				// We have the match. No actions taken.
			} else {
				// We dont have that match in our DB. We will check the matchlist to see which game we don't have and download it from the API.
				$gamesToFind[$key] = $matchlist->matches[$key]->gameId;
			}
		}
		if (isset($gamesToFind)) {
			$lol = $this->makeApiRequest();
			$result = $lol->getMatchById($gamesToFind);

			// Store the values to DB
			$this->setMatchById($result);
		}
		// We combine the Db array and the APi array since we may have mising games in our DB
		// On repeat requests we will have all the games in our db. No need to check
		if (isset($result)) {
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
			// Then we sort those 2 arrays by their key
			ksort($result);
		}
		else {
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
		}
		// Convert to MatchById Class
		foreach ($result as $key => $value) {
			if (is_array($value)) {
				$resultAssoc = json_decode($result[$key]["matchJson"], true);
				$matchById[$key] = new Objects\MatchById($resultAssoc);
			} else {
				$matchById[$key] = $result[$key];
			}
		}
		return $matchById;
	}

	public function getMatchByIdSingle($gameId)
	{
		$selectQuery = "";

		$selectQuery = $selectQuery . "SELECT * FROM `gamebyid_$this->region` WHERE `gameId` = '$gameId'";

		$resultDb = $this->makeDbCallGet($selectQuery);
		// Find Missing games
		foreach ($resultDb as $key => $value) {
			if (isset($value)) {
				// We have the match. No actions taken.
			} else {
				// We dont have that match in our DB. We will check the matchlist to see which game we don't have and download it from the API.
				$gamesToFind[$key] = $gameId;
			}
		}
		if (isset($gamesToFind)) {
			$lol = $this->makeApiRequest();
			$result = $lol->getMatchById($gamesToFind);

			// Store the values to DB
			$this->setMatchById($result);
		}
		// We combine the Db array and the APi array since we may have mising games in our DB
		// On repeat requests we will have all the games in our db. No need to check
		if (isset($result)) {
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
			// Then we sort those 2 arrays by their key
			ksort($result);
		}
		else {
			foreach ($resultDb as $key => $value) {
				if (isset($value)) {
					$result[$key] = $value;
				}
			}
		}
		// Convert to MatchById Class
		foreach ($result as $key => $value) {
			if (is_array($value)) {
				$resultAssoc = json_decode($result[$key]["matchJson"], true);
				$matchById[$key] = new Objects\MatchById($resultAssoc);
			} else {
				$matchById[$key] = $result[$key];
			}
		}
		return $matchById;
	}


	/**
	 *  @param \API\LeagueAPI\Objects\MatchById[] $matchById 
	 * 
	 */
	public function setMatchById(array $matchById)
	{
		$insertQuery = "INSERT IGNORE INTO `gamebyid_$this->region` (`gameId`, `matchJson`) VALUES ";


		foreach ($matchById as $key => $value) {
			$json[$key] = json_encode($matchById[$key], JSON_UNESCAPED_UNICODE );
			$what[$key] = $key;
			$jsonInsert = $json[$key];
			if (array_key_last($matchById) == $key) {
				$insertQuery .= "('$value->gameId', '$jsonInsert');";
			}
			else {

				$insertQuery .= "('$value->gameId', '$jsonInsert'),";
			}
		}
		$this->makeDbCallSet($insertQuery);
	}
	/** @param \API\LeagueAPI\Objects\LeagueSummoner[][] $summonersLeagues */
	public function setLeagueBySummoner($summonersLeagues, $entriesToFind = null)
	{
		$insertQuery = "INSERT IGNORE INTO `leaguebysummoner_$this->region` (`summonerId`, `summonerName`, `queueType`, `tier`, `rank`, `leagueId`, `leaguePoints`, `wins`, `losses`, `veteran`, `inactive`, `freshBlood`, `hotStreak`, `isNull`) VALUES ";
		// If a single value is passed

		foreach ($summonersLeagues as $key => $participant) {
			if ($participant === null) {
				$entry = $entriesToFind[$key];
				if (array_key_last($summonersLeagues) == $key) {
					$insertQuery .= "('$entry', null, 'Unranked', null, null, null, null, null, null, null, null, null, null, 1);";
				}
				else{
					$insertQuery .= "('$entry', null, 'Unranked', null, null, null, null, null, null, null, null, null, null, 1),";
				}
			}
			else{
				foreach ($participant as $key2 => $league) {
			
					if (array_key_last($summonersLeagues) == $key && array_key_last($participant) == $key2){
						$insertQuery .= "('$league->summonerId', '$league->summonerName', '$league->queueType', '$league->tier', '$league->rank', '$league->leagueId', '$league->leaguePoints', '$league->wins', '$league->losses', '$league->veteran', '$league->inactive', '$league->freshBlood', '$league->hotStreak', '0');";
					}
					else {
						$insertQuery .= "('$league->summonerId', '$league->summonerName', '$league->queueType', '$league->tier', '$league->rank', '$league->leagueId', '$league->leaguePoints', '$league->wins', '$league->losses', '$league->veteran', '$league->inactive', '$league->freshBlood', '$league->hotStreak', '0'),";
					}
				}
			}
			
		}

		$this->makeDbCallSet($insertQuery);
	}
	public function setLeagueBySummonerSingle($entry, $summonerId)
	{
		$insertQuery = "INSERT IGNORE INTO `leaguebysummoner_$this->region` (`summonerId`, `summonerName`, `queueType`, `tier`, `rank`, `leagueId`, `leaguePoints`, `wins`, `losses`, `veteran`, `inactive`, `freshBlood`, `hotStreak`, `isNull`) VALUES ";
		$i = 0;
		if (empty($entry)) {
			$insertQuery .= "('$summonerId', null, 'Unranked', null, null, null, null, null, null, null, null, null, null, 1);";
		}
		else{
			foreach ($entry as $key => $value) {
				$i++;
				// last element
				if (sizeof($entry) == $i) {
					$insertQuery .= "('$value->summonerId', '$value->summonerName', '$value->queueType', '$value->tier', '$value->rank', '$value->leagueId', '$value->leaguePoints', '$value->wins', '$value->losses', '$value->veteran', '$value->inactive', '$value->freshBlood', '$value->hotStreak', '0');";
				}
				else{
					$insertQuery .= "('$value->summonerId', '$value->summonerName', '$value->queueType', '$value->tier', '$value->rank', '$value->leagueId', '$value->leaguePoints', '$value->wins', '$value->losses', '$value->veteran', '$value->inactive', '$value->freshBlood', '$value->hotStreak', '0'),";
				}
			}
		}

		$this->makeDbCallSet($insertQuery);
	}

	/** 
	 * THIS API CAN RETURN NULL
	 * @param \API\LeagueAPI\Objects\Summoner[][] $match  
	*/
	public function getLeagueSummoner(array $summonerIds)
	{
		$selectQuery = "";

        foreach ($summonerIds as $key => $summoner) {
			$selectQuery .= "SELECT * FROM `leaguebysummoner_$this->region` WHERE `summonerId` = '$summoner';";
		}
		$resultDb = $this->makeDbCallGetMulti($selectQuery);


		// Find Missing Entries 
		foreach ($resultDb as $key => $value) {
			if (!$value == null) {
				// We have the entry. No actions taken.
			} else {
				// We dont have that entry in our DB. We will check the summoners to see which game we don't have and download it from the API.
				$entriesToFind[$key] = $summonerIds[$key];
			}
		}

		if (isset($entriesToFind)) {

			$lol = $this->makeApiRequest();

			$result = $lol->getLeagueSummoner($entriesToFind);

			// Store the values to DB
			$this->setLeagueBySummoner($result, $entriesToFind);

		};

		$selectQuery = "";
        foreach ($summonerIds as $key => $summoner) {
			$selectQuery .= "SELECT * FROM `leaguebysummoner_$this->region` WHERE `summonerId` = '$summoner';";
		}

		$resultDb = $this->makeDbCallGetMulti($selectQuery);

		// COnvvert the array to an PHP object if needed.

		// Reorganize the DB array
		// foreach ($resultDb as $key => $match) {
		// 	if (isset($match)) {
		// 		if (isset($match["summonerId"])) {
		// 				unset($resultDb[$key]);
		// 				$resultDb[$key][$match["queueType"]] =  new Objects\LeagueSummoner($match);
		// 		}
		// 		else {
		// 			foreach ($match as $key2 => $value) {
		// 				unset($resultDb[$key][$key2]);
		// 				$resultDb[$key][$value["queueType"]] = new Objects\LeagueSummoner($value);	
		// 			}
		// 		}
		// 	}
		// 	ksort($resultDb[$key]); 
		// }

		$result = $resultDb;

		// // We combine the Db array and the APi array since we may have mising games in our DB
		// // On repeat requests we will have all the games in our db. No need to check 
		// if (isset($resultDb)) {
		// 	if (isset($result)) {
		// 		foreach ($resultDb as $key => $value) {
		// 			if (isset($value)) {
		// 				$result[$key] = $value;
		// 			}
		// 		}
		// 		// Then we sort those 2 arrays by their key
		// 		ksort($result);
		// 	}
		// 	else{
		// 		$result = $resultDb;
		// 	}
		//}

		return $result;
	}
	 /** Can return NULL */
	public function getLeagueSummonerSingle(string $summonerId)
	{
		$selectQuery = "SELECT * FROM `leaguebysummoner_$this->region` WHERE `summonerId` = '$summonerId';";

		$data = $this->makeDbCallGet($selectQuery);
		// We have the some data in our DB
		if (isset($data)) {
			// The value can be a single array or a nested array with at least 2 elements
			// if it's a nested array
			if (isset($data[0])) {
				foreach ($data as $key => $value) {
					$entry[$value["queueType"]] = new Objects\LeagueSummoner($value);
				}
			}
			else{
				$entry[$data["queueType"]] = new Objects\LeagueSummoner($data);
			}
			return $entry;
		}
		// No data. Get it
		else{
			$entry = $this->makeApiRequest()->getLeagueSummonerSingle($summonerId);

			$this->setLeagueBySummonerSingle($entry, $summonerId);
			return $entry;
		}
	}
	public function getTimeline(string $region)
	{
		throw new Exception("Unimplemented");
		return;
	}
	/** @var matchTimeline $timeline */
	public function setTimeline(string $region, $timeline)
	{
		throw new Exception("Unimplemented");
		return;
	}

	public function updateSummoner(Objects\Summoner $summoner)
	{
		$trimmedName = str_replace(' ', '', $summoner->name);
		$updateQuery = "UPDATE `summoner_{$this->region}`SET `summonerLevel`='{$summoner->summonerLevel}',`name`='{$summoner->name}',`trimmedName`='{$trimmedName}', `profileIconId`='{$summoner->profileIconId}'WHERE `accountId`='{$summoner->accountId}'";
		$this->makeDbCallSet($updateQuery);
	}

	public function updateUpdateSummonerLastMatchlist($nowUpdated, $accountId)
	{
		$now = date('Y-m-d H:i:s', $nowUpdated);
		$updateQuery = "UPDATE `summoner_{$this->region}`SET `lastUpdateMatchlist`='{$now}' WHERE `accountId`='{$accountId}'";
		$this->makeDbCallSet($updateQuery);
	}

	private function makeApiRequest()
	{
		$lol = new LeagueAPI([
            LeagueAPI::SET_REGION => $this->settings[self::SET_REGION],
        ]);
		return $lol;
	}

	public function setRequests($request)
	{
		if (isset($request["retry-after"])) {
			$retry = $request["retry-after"];
		}
		else{
			$retry = null;
		}
		$timestamp = strtotime($request["date"]);

		$sql = "INSERT INTO `rate_limit_$this->region` (`app-rate-limit`, `app-rate-limit-count`, `method-rate-limit`, `method-rate-limit-count`, `targetUrl`, `response_code`, `date`, `retry-after`)VALUES ('{$request["x-app-rate-limit"]}', '{$request["x-app-rate-limit-count"]}', '{$request["x-method-rate-limit"]}', '{$request["x-method-rate-limit-count"]}', '{$request["targetUrl"]}', '{$request["response_code"]}', '{$timestamp}', '{$retry}')";


		$this->makeDbCallSet($sql);
	}

	public function setRequestsMulti($request)
	{
		$request = array_values($request);

		foreach ($request as $key => $value) {
			if (isset($request[$key]["retry-after"])) {
				$retry = $request[$key]["retry-after"];
			}
			else{
				$retry[$key] = null;
			}
			$timestamp[$key] = strtotime($request[$key]["date"]);
		}
		$sql = "INSERT INTO `rate_limit_$this->region` (`app-rate-limit`, `app-rate-limit-count`, `method-rate-limit`, `method-rate-limit-count`, `targetUrl`, `response_code`, `date`, `retry-after`) VALUES ";
		foreach ($request as $key => $value) {
			if (sizeof($request) == $key + 1) {

				$sql .= "('{$request[$key]["x-app-rate-limit"]}', '{$request[$key]["x-app-rate-limit-count"]}', '{$request[$key]["x-method-rate-limit"]}', '{$request[$key]["x-method-rate-limit-count"]}', '{$request[$key]["targetUrl"]}', '{$request[$key]["response_code"]}', '{$timestamp[$key]}', '{$retry[$key]}');";
			}
			else
			{
				$sql .= "('{$request[$key]["x-app-rate-limit"]}', '{$request[$key]["x-app-rate-limit-count"]}', '{$request[$key]["x-method-rate-limit"]}', '{$request[$key]["x-method-rate-limit-count"]}', '{$request[$key]["targetUrl"]}', '{$request[$key]["response_code"]}', '{$timestamp[$key]}', '{$retry[$key]}'),";
			}
		}
		$this->makeDbCallSet($sql);
	}

	public function splitMulti($request)
	{

		$appRateLimitCount = [];
		$appRateLimit = [];
		$methodRateLimitCount = [];
		$methodRateLimit = [];
            foreach (explode(',', $request["x-app-rate-limit-count"]) as $key => $limitInterval) {
                $limitInterval = explode(':', $limitInterval);
                $limit         = (int)$limitInterval[0];
                $interval      = (int)$limitInterval[1];

                $appRateLimitCount[$key][$interval] = $limit;
			}
			

            foreach (explode(',', $request["x-app-rate-limit"]) as $key => $limitInterval) {
                $limitInterval = explode(':', $limitInterval);
                $limit         = (int)$limitInterval[0];
                $interval      = (int)$limitInterval[1];

                $appRateLimit[$key][$interval] = $limit;
            }
            
            foreach (explode(',', $request["x-method-rate-limit-count"]) as $key => $limitInterval) {
                $limitInterval = explode(':', $limitInterval);
                $limit         = (int)$limitInterval[0];
                $interval      = (int)$limitInterval[1];

                $methodRateLimitCount[$key][$interval] = $limit;
            }

            foreach (explode(',', $request["x-method-rate-limit"]) as $key => $limitInterval) {
                $limitInterval = explode(':', $limitInterval);
                $limit         = (int)$limitInterval[0];
                $interval      = (int)$limitInterval[1];

                $methodRateLimit[$key][$interval] = $limit;
			}
		$headers["x-app-rate-limit-count"] = $appRateLimitCount;
		$headers["x-app-rate-limit"] = $appRateLimit;
		$headers["x-method-rate-limit-count"] = $methodRateLimitCount;
		$headers["x-method-rate-limit"] = $methodRateLimit;

		return $headers;
	}

	public function split($request)
	{
		$appRateLimitCount = [];
		foreach (explode(',', $request["x-app-rate-limit-count"]) as $limitInterval)
		{
			$limitInterval = explode(':', $limitInterval);
			$limit         = (int)$limitInterval[0];
			$interval      = (int)$limitInterval[1];

			$appRateLimitCount[$interval] = $limit;
		}

		$appRateLimit = [];
		foreach (explode(',', $request["x-app-rate-limit"]) as $limitInterval)
		{
			$limitInterval = explode(':', $limitInterval);
			$limit         = (int)$limitInterval[0];
			$interval      = (int)$limitInterval[1];

			$appRateLimit[$interval] = $limit;
		}

		$methodRateLimitCount = [];
		foreach (explode(',', $request["x-app-rate-limit-count"]) as $limitInterval)
		{
			$limitInterval = explode(':', $limitInterval);
			$limit         = (int)$limitInterval[0];
			$interval      = (int)$limitInterval[1];

			$methodRateLimitCount[$interval] = $limit;
		}

		$methodRateLimit = [];
		foreach (explode(',', $request["x-app-rate-limit"]) as $limitInterval)
		{
			$limitInterval = explode(':', $limitInterval);
			$limit         = (int)$limitInterval[0];
			$interval      = (int)$limitInterval[1];

			$methodRateLimit[$interval] = $limit;
		}

		$headers["x-app-rate-limit-count"] = $appRateLimitCount;
		$headers["x-app-rate-limit"] = $appRateLimit;
		$headers["x-method-rate-limit-count"] = $methodRateLimitCount;
		$headers["x-method-rate-limit"] = $methodRateLimit;


		return $headers;
	}

	private function setPlatform($region)
	{
		$platform = $this->platforms->getPlatformName($region);
		
		return $platform;
	}
}