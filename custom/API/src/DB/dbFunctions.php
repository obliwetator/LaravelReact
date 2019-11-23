<?php

namespace API\dbCall;

use API\LeagueAPI\LeagueAPI;
use API\LeagueAPI\Objects;

require_once("dbConnect.php");
class dbCall
{
	/** @var \mysqli $conn */
	public $conn;
	

	private function openCon(string $dbRegion)
	{
		$this->conn = DbOpenConn($dbRegion);
	}
	private function closeCon()
	{
		$this->conn->close();
	}

	private function makeDbCallGet(string $region, string $query)
	{
		$this->openCon($region);
		$queryResult = $this->conn->query($query);
		if ($queryResult == true) {

			if ($queryResult->num_rows >= 1) {
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
			eh("something went wrong with the SQL query GET. " . $this->conn->error);
		}
		$this->closeCon($this->conn);

		return $resultAssoc;
	}

	private function makeDbCallGetMulti(string $region, string $query)
	{

		$this->openCon($region);
		$matches = [];
		$x = 0;
		if ($this->conn->multi_query($query)) {
			do {
				/* store first result set */
				if ($result = $this->conn->store_result()) {
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

	private function makeDbCallGetMultiLeague(string $region, string $query)
	{
		$this->openCon($region);
		$matches = [];
		$x = 0;
		if ($this->conn->multi_query($query)) {
			do {
				/* store first result set */
				if ($result = $this->conn->store_result()) {
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

	private function makeDbCallSet(string $region, string $query)
	{
		$this->openCon($region);
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
			eh("something went wrong with the SQL query SET. " . $this->conn->error);
		}

		$this->closeCon($this->conn);
	}

	private function makeDbCallSetMulti(string $region, string $query)
	{
		
	}
	/** @return Objects\Summoner */
	public function getSummoner(string $region,	$summonerName)
	{
		$summonerName = str_replace(' ', '', $summonerName);

		$selectQuery = "SELECT * FROM `summoner_$region` WHERE `trimmedName` = '$summonerName'";

		$resultAssoc = $this->makeDbCallGet($region, $selectQuery);


		// First time we lookup. If it doesn't exist make an API request and put it in the DB.
		if ($resultAssoc == null) {
			$lol = $this->makeApiRequest();
			$summonerDataDb = $lol->getSummonerNameSingle($region, $summonerName);
			if (isset($summonerDataDb)) {
				$this->setSummonerSingle($region, $summonerDataDb);
			}
		} else {
			$resultAssoc = $resultAssoc[0];
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
		$resultDb = $this->makeDbCallGetMulti($region, $selectQuery);


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

		

		$this->makeDbCallSet($region, $insertQuery);
	}

	public function setSummonerSingle(string $region, $summoner)
	{
		$insertQuery = "INSERT IGNORE INTO `summoner_$region`(`id`, `accountId`, `puuid`, `name`, `profileIconId`, `revisionDate`, `summonerLevel`, `trimmedName`)
		VALUES ('$summoner->id','$summoner->accountId','$summoner->puuid','$summoner->name','$summoner->profileIconId','$summoner->revisionDate','$summoner->summonerLevel','$summoner->trimmedName');";

		$this->makeDbCallSet($region, $insertQuery);
	}
	/** @return null|Objects\MatchList */
	public function getMatchlist($region, string $accountId, int $limit, $queue = null, $season = null, $champion = null, $beginTime = null, $endTime = null, $beginIndex = null)
	{
		$selectQuery = "SELECT * FROM `matchlist_$region` WHERE `accountId` = '$accountId' ORDER BY `matchlist_$region`.`timestamp` DESC LIMIT $limit";

		$resultAssoc = $this->makeDbCallGet($region, $selectQuery);

		// No matchlist in DB
		if ($resultAssoc == null)
		{
			$lol = $this->makeApiRequest();
			$dbMatchlist = $lol->getMatchlist($region, $accountId, $queue, $season, $champion, $beginTime, $endTime, $beginIndex, $limit);
			if (isset($dbMatchlist)) {
				$this->setMatchlist($region, $dbMatchlist, $accountId);
			}
			return $dbMatchlist;
		}
		else {
			// The requested ammount of games dont match the games we have in our DB. Try to get them
			if (sizeof($resultAssoc) < $limit) {
				$lol = $this->makeApiRequest();
				$dbMatchlist = $lol->getMatchlist($region, $accountId, $queue, $season, $champion, $beginTime, $endTime, $beginIndex, $limit);
				if (isset($dbMatchlist)) {
					$this->setMatchlist($region, $dbMatchlist, $accountId);
				}
			}
			else{
				$result["matches"] = $resultAssoc;
				$dbMatchlist = new Objects\MatchList($result);
			}

			return $dbMatchlist;
		}
	}

	public function setMatchlist($region, Objects\MatchList $getMatchlist, string $accountId)
	{
		$value = "";
		$selectQuery2 = "";
		// We check which of those games we have in our DB for that spacific summoner
		// If the result returned is null that means that gameId is not in our db
		// In this case we should get the game from the API
		for ($i = 0; $i < sizeof($getMatchlist->matches); $i++) {
			$matches = $getMatchlist->matches[$i];
			$selectQuery2 .= "SELECT * FROM `matchlist_$region` WHERE `gameId` = '$matches->gameId' AND `accountId` = '$accountId';";
		}


		// Returns an array with the format $dbMatchlist[]["propertyName"]
		$dbMatchlist = $this->makeDbCallGetMulti($region, $selectQuery2);


		// We get the game that matches the gameIds in our DB and check them up against the API data to decide which games we will store in our DB

			for ($i = 0; $i < sizeof($dbMatchlist); $i++) {
				// We will create the sql query if we DONT have the game in our db
				// If we dont have the game in our DB the value of dbMatchlist array at that index will be NULL

				// We dont have the game in our DB. We will create the query
				if ($dbMatchlist[$i] == null) {
					// Makes the construction of the query easier
					$match = $getMatchlist->matches[$i];
					// If its the last element of the list we ad an ;
					if (sizeof($dbMatchlist) == $i + 1) {
						$value .= "('$accountId', '$match->gameId', '$match->platformId', '$match->champion', '$match->queue', '$match->season', '$match->timestamp', '$match->role', '$match->lane');";
					} else {
						$value .= "('$accountId', '$match->gameId', '$match->platformId', '$match->champion', '$match->queue', '$match->season', '$match->timestamp', '$match->role', '$match->lane'), \n";
					}
				} else {
					// We have the game in our DB. We do nothing in this case
				}
			}
			// We initialize the $value with "" so if default present is empty it means that the value isn't set
		if ($value != "") {
			$insertQuery = "INSERT INTO `matchlist_$region`(`accountId`, `gameId`,`platformId`,`champion`,`queue`, `season`, `timestamp`, `role`, `lane`) VALUES $value";

			$this->makeDbCallSet($region, $insertQuery);
		} else {
			// We have nothing to add to DB which means we do nothing
		}
	}

	/** Retuns an array of Matches
	 * @var Objects\MatchById[] $matchById
	 * @return Objects\MatchById[]*/
	public function getMatchById(string $region, Objects\MatchList $matchlist)
	{
		$selectQuery = "";
		foreach ($matchlist->matches as $key => $value) {
			$matchIds[$key] = $matchlist->matches[$key]->gameId;
			$selectQuery = $selectQuery . "SELECT * FROM `gamebyid_eun1` WHERE `gameId` = " . $matchIds[$key] . " ORDER BY `gameId` DESC;\n";
		}
		$resultDb = $this->makeDbCallGetMulti($region, $selectQuery);

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
			$result = $lol->getMatchById($region, $gamesToFind);

			// Store the values to DB
			$this->setMatchById($region, $result);
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

	public function getMatchByIdSingle(string $region, $gameId)
	{
		$selectQuery = "";

		$selectQuery = $selectQuery . "SELECT * FROM `gamebyid_eun1` WHERE `gameId` = '$gameId'";

		$resultDb = $this->makeDbCallGet($region, $selectQuery);
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
			$result = $lol->getMatchById($region, $gamesToFind);

			// Store the values to DB
			$this->setMatchById($region, $result);
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
	public function setMatchById(string $region,array $matchById)
	{
		$insertQuery = "INSERT IGNORE INTO `gamebyid_eun1` (`gameId`, `matchJson`) VALUES ";


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
		$this->makeDbCallSet($region, $insertQuery);
	}
	/** @param \API\LeagueAPI\Objects\LeagueSummoner[][][] $summonersLeagues */
	public function setLeagueBySummoner(string $region,$summonersLeagues, $entriesToFind = null)
	{
		$insertQuery = "INSERT IGNORE INTO `leaguebysummoner_eun1` (`summonerId`, `summonerName`, `queueType`, `tier`, `rank`, `leagueId`, `leaguePoints`, `wins`, `losses`, `veteran`, `inactive`, `freshBlood`, `hotStreak`, `isNull`) VALUES ";
		// If a single value is passed
		foreach ($summonersLeagues as $key => $match) {
			foreach ($match as $key2 => $participants) {
					if ($participants == null) {
						$entry = $entriesToFind[$key][$key2];
						if (array_key_last($summonersLeagues) == $key && array_key_last($match) == $key2) {
							$insertQuery .= "('$entry', null, 'Unranked', null, null, null, null, null, null, null, null, null, null, 1);";
						}
						else{
							$insertQuery .= "('$entry', null, 'Unranked', null, null, null, null, null, null, null, null, null, null, 1),";
						}
					}
					else{
						foreach ($participants as $key3 => $league) {
					
							if (array_key_last($summonersLeagues) == $key && array_key_last($match) == $key2 && array_key_last($participants) == $key3){
								$insertQuery .= "('$league->summonerId', '$league->summonerName', '$league->queueType', '$league->tier', '$league->rank', '$league->leagueId', '$league->leaguePoints', '$league->wins', '$league->losses', '$league->veteran', '$league->inactive', '$league->freshBlood', '$league->hotStreak', '0');";
							}
							else {
								$insertQuery .= "('$league->summonerId', '$league->summonerName', '$league->queueType', '$league->tier', '$league->rank', '$league->leagueId', '$league->leaguePoints', '$league->wins', '$league->losses', '$league->veteran', '$league->inactive', '$league->freshBlood', '$league->hotStreak', '0'),";
							}
						}
					}
			}
		}
	
			// we will get an array of arrays of arrays -> object
		$this->makeDbCallSet($region,$insertQuery);
	}
	public function setLeagueBySummonerSingle(string $region,$entry, $summonerId)
	{
		$insertQuery = "INSERT IGNORE INTO `leaguebysummoner_eun1` (`summonerId`, `summonerName`, `queueType`, `tier`, `rank`, `leagueId`, `leaguePoints`, `wins`, `losses`, `veteran`, `inactive`, `freshBlood`, `hotStreak`, `isNull`) VALUES ";
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

		$this->makeDbCallSet($region,$insertQuery);
	}

	/** 
	 * THIS API CAN RETURN NULL
	 * @param \API\LeagueAPI\Objects\Summoner[][] $match  
	*/
	public function getLeagueSummoner(string $region, array $match)
	{
		// Find unique summoners
		// If the $match array has more than 1 element we will compare those arrays for unique summoners
		$mirror = $match;

		if (sizeof($match) > 1){
			foreach ($match as $key => $summoners) {
				foreach ($summoners as $key2 => $summoner) { 
					if(isset($match[$key + 1])) {
    	                for ($i=0; $i < sizeof($summoners); $i++) {
    	                    if (isset($match[$key + 1][$key2])) {
    	                        if ($match[$key + 1][$key2] == $match[$key][$i]) {
									$duplicateEntry[$key + 1][$key2] = $match[$key + 1][$key2];
									$summonersIndex[$key][$i] = $match[$key][$i];
								}
    	                    }
    	                }
    	            }
				}
				// reorganize the array
				// $mirror[$key] = array_values($mirror[$key]);
			}
			$match = $mirror;
		}
		$selectQuery = "";
        foreach ($match as $key => $summoners) {
			foreach ($summoners as $key2 => $summoner) {
				$selectQuery .= "SELECT * FROM `leaguebysummoner_eun1` WHERE `summonerId` = '$summoner';";
			}
			$resultDb[$key] = $this->makeDbCallGetMulti($region, $selectQuery);
			//   Reset the query
			$selectQuery = "";
		}
		// Find Missing Entries 
		foreach ($resultDb as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (!$value2 == null) {
					// We have the entry. No actions taken.

				} else {
					// We dont have that entry in our DB. We will check the summoners to see which game we don't have and download it from the API.
					$entriesToFind[$key][$key2] = $match[$key][$key2];
				}
			}
		}

		if (isset($entriesToFind)) {

			$lol = $this->makeApiRequest();

			$result = $lol->getLeagueSummoner($region, $entriesToFind);
			if(isset($duplicateEntry))
			{
				// Remove duplicate games when adding to DB
				foreach ($duplicateEntry as $key => $value) {
            	    foreach ($value as $key2 => $value2) {
            	        // If we have an entry as a duplicate and its NOT in the result that means its in the resultDb array
            	        if (isset($result[$key][$key2])) {
            	            $result[$key][$key2] = $result[$key][$key2];
            	            unset($result[$key][$key2]);
            	        }
            	    }
				}
			}
			// Store the values to DB
			$this->setLeagueBySummoner($region, $result, $entriesToFind);

			// Re-add the duplicate games to pass it to the view
			foreach ($result as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$result[$key][$key2] = $result[$key][$key2];
				}
				ksort($result[$key]);
			}
		};

		$selectQuery = "";
        foreach ($match as $key => $summoners) {
			foreach ($summoners as $key2 => $summoner) {
				$selectQuery .= "SELECT * FROM `leaguebysummoner_eun1` WHERE `summonerId` = '$summoner';";
			}
			$resultDb[$key] = $this->makeDbCallGetMulti($region, $selectQuery);
			//   Reset the query
			$selectQuery = "";
		}

		// Reorganize the DB array
		foreach ($resultDb as $key => $match) {
			foreach ($match as $key2 => $value) {
				if (isset($value)) {
					if (isset($value["summonerId"])) {
							unset($resultDb[$key][$key2]);
							$resultDb[$key][$key2][$value["queueType"]] =  new Objects\LeagueSummoner($value);
					}
					else {
						foreach ($value as $key3 => $value2) {
							unset($resultDb[$key][$key2][$key3]);
							$resultDb[$key][$key2][$value2["queueType"]] = new Objects\LeagueSummoner($value2);	
						}
					}
				}
			}
			ksort($resultDb[$key]); 
		}
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
	public function getLeagueSummonerSingle(string $region, string $summonerId)
	{
		$selectQuery = "SELECT * FROM `leaguebysummoner_eun1` WHERE `summonerId` = '$summonerId';";

		$data = $this->makeDbCallGet($region,$selectQuery);

		// We have the some data in our DB
		if (isset($data)) {
			foreach ($data as $key => $value) {
				$entry[$value["queueType"]] = new Objects\LeagueSummoner($value);
			}
			return $entry;
		}
		// No data. Get it
		else{
			$lol = $this->makeApiRequest();

			$entry = $lol->getLeagueSummonerSingle($region, $summonerId);

			$this->setLeagueBySummonerSingle($region, $entry, $summonerId);
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
	private function makeApiRequest()
	{
		$lol = new LeagueAPI();
		return $lol;
	}

	public function setRequests(string $region, $request)
	{
		if (isset($request["retry-after"])) {
			$retry = $request["retry-after"];
		}
		else{
			$retry = null;
		}
		$timestamp = strtotime($request["date"]);

		$sql = "INSERT INTO `rate_limit_$region` (`app-rate-limit`, `app-rate-limit-count`, `method-rate-limit`, `method-rate-limit-count`, `targetUrl`, `response_code`, `date`, `retry-after`)VALUES ('{$request["x-app-rate-limit"]}', '{$request["x-app-rate-limit-count"]}', '{$request["x-method-rate-limit"]}', '{$request["x-method-rate-limit-count"]}', '{$request["targetUrl"]}', '{$request["response_code"]}', '{$timestamp}', '{$retry}')";


		$this->makeDbCallSet($region, $sql);
	}

	public function setRequestsMulti(string $region, $request)
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
		$sql = "INSERT INTO `rate_limit_$region` (`app-rate-limit`, `app-rate-limit-count`, `method-rate-limit`, `method-rate-limit-count`, `targetUrl`, `response_code`, `date`, `retry-after`) VALUES ";
		foreach ($request as $key => $value) {
			if (sizeof($request) == $key + 1) {

				$sql .= "('{$request[$key]["x-app-rate-limit"]}', '{$request[$key]["x-app-rate-limit-count"]}', '{$request[$key]["x-method-rate-limit"]}', '{$request[$key]["x-method-rate-limit-count"]}', '{$request[$key]["targetUrl"]}', '{$request[$key]["response_code"]}', '{$timestamp[$key]}', '{$retry[$key]}');";
			}
			else
			{
				$sql .= "('{$request[$key]["x-app-rate-limit"]}', '{$request[$key]["x-app-rate-limit-count"]}', '{$request[$key]["x-method-rate-limit"]}', '{$request[$key]["x-method-rate-limit-count"]}', '{$request[$key]["targetUrl"]}', '{$request[$key]["response_code"]}', '{$timestamp[$key]}', '{$retry[$key]}'),";
			}
		}
		$this->makeDbCallSet($region, $sql);
	}

	public function getRequests(string $region)
	{
		$this->openCon($region);
		$this->conn->prepare("");


		$this->closeCon($this->conn);
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
}