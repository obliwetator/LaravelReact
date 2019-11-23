<?php

namespace API\LeagueAPI\Objects;
use LeagueAPI\LeagueAPI;

class objectInit
{
	private static function getPropertyDataType(string $phpDocComment)
	{
		$o = new \stdClass();

		preg_match('/@var\s+(\w+)(\[\])?/', $phpDocComment, $matches);

		$o->class = $matches[1];
		$o->isArray = isset($matches[2]);

		if (in_array($o->class, ['integer', 'int', 'string', 'bool', 'boolean', 'double', 'float', 'array']))
			return false;

		return $o;
	}
	// This converts the Array from the API requests to the resposonding object
	public function __construct($data, LeagueAPI $api = null)
	{
		$reflectionObj = new \ReflectionClass($this);
		$namespace = $reflectionObj->getNamespaceName();
		$iterableProp = $reflectionObj->hasProperty('_iterable')
		? self::getIterablePropertyName($reflectionObj->getDocComment())
		: false;
		$linkableProp = $reflectionObj->hasProperty('staticData')
		? self::getLinkablePropertyData($reflectionObj->getDocComment())
		: [ 'function' => false, 'parameter' => false ];	


		foreach ($data as $property => $value) {
			try {
				if ($propRef = $reflectionObj->getProperty($property))
				{
					// Object has required property, time to discover if it's
					$dataType = self::getPropertyDataType($propRef->getDocComment());
					if ($dataType !== false && is_array($value))
					{
						//  Property is special DataType
						$newRef = new \ReflectionClass("$namespace\\$dataType->class");
						if ($dataType->isArray)
						{
							//  Property is array of special DataType (another API object)
							foreach ($value as $identifier => $d)
								$this->$property[$identifier] = $newRef->newInstance($d, $api);
						} else
						{
							//  Property is special DataType (another API object)
							$this->$property = $newRef->newInstance($value, $api);
						}
					}
					else
					{
						//  Property is general value
						$this->$property = $value;
					}
				}
				if ($iterableProp == $property)
				$this->_iterable = $this->$property;

			} catch (\Throwable $th) {
				//throw $th;
			}
		}

		// $x = 0;
		// switch ($data["class"]) {
		// 	case 'summoner':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			$this->$key = $value;
		// 		}
		// 		break;
		// 	case 'matchbyid':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			if (property_exists($this, $key)) {
		// 				if ($key == "teams") {
		// 					$x = 0;
		// 					// Team - 2 arrays // Loops twice(2)
		// 					foreach ($value as $key2 => $value2) {
		// 						$Team = new Team();

		// 						// Assign Team objects
		// 						foreach ($value2 as $key5 => $value5) {
		// 							if ($key5 == "bans") {
		// 								//
		// 							} else {
		// 								$Team->$key5 = $value5;
		// 							}
		// 						}
		// 						if (isset($value5)) {
		// 							foreach ($value2["bans"] as $key3 => $value3) {
		// 								// Ban Team 
		// 								$Ban = new Ban();
		// 								// every ban object
		// 								foreach ($value3 as $key4 => $value4) {
		// 									$Ban->$key4 = $value4;
		// 								}
		// 								//$Team->$key = $value;
		// 								$Team->bans[$key3] = $Ban;
		// 							}
		// 						}
		// 						// Assign Team objects to matchById Object
		// 						$this->teams[$x] = $Team;
		// 						$x++;
		// 					}
		// 				} elseif ($key == "participants") {
		// 					// Participant class x Arrays (10)
		// 					foreach ($value as $key2 => $value2) {
		// 						$participant = new Participant();
		// 						$stat = new Stat();
		// 						$timeline = new Timeline();

		// 						// values / Stats/ Timeline
		// 						foreach ($value2 as $key3 => $value3) {

		// 							if ($key3 == "stats") {
		// 								foreach ($value3 as $key4 => $value4) {
		// 									$stat->$key4 = $value4;
		// 								}
		// 								$participant->stats = $stat;
		// 							} elseif ($key3 == "timeline") {
		// 								foreach ($value3 as $key4 => $value4) {
		// 									if ($key4 == "role" || $key4 == "lane" || $key4 == "participantId") {
		// 										$timeline->$key4 = $value4;
		// 									} else {
		// 										if (isset($value4)) {
		// 											foreach ($value4 as $key5 => $value5) {
		// 												$timeline->$key4[$key5] = $value5;
		// 											}
		// 										}
		// 									}
		// 								}
		// 							} else {
		// 								$participant->$key3 = $value3;
		// 							}
		// 						}
		// 						$this->participants[$key2] = $participant;
		// 						$this->participants[$key2]->timeline = $timeline;
		// 					}
		// 				} elseif ($key == "participantIdentities") {

		// 					// $key2 = array index / $value2 = the whole arrays
		// 					foreach ($value as $key2 => $value2) {
		// 						$participantIdentities = new ParticipantIdentity();
		// 						$player = new Player();
		// 						foreach ($value2 as $key3 => $value3) {
		// 							if ($key3 == "participantId") {
		// 								$participantIdentities->participantId = $value3;
		// 							} else {
		// 								foreach ($value3 as $key4 => $value4) {
		// 									$player->$key4 = $value4;
		// 								}
		// 								$participantIdentities->player = $player;
		// 							}
		// 						}
		// 						$this->participantIdentities[$key2] = $participantIdentities;
		// 					}
		// 				} else {
		// 					$this->$key = $value;
		// 				}
		// 			} else { }
		// 		}
		// 		break;
		// 	case 'matchlist':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			if ($key == "matches") {
		// 				foreach ($value as $key2 => $value2) {
		// 					$match = new Match();
		// 					foreach ($value2 as $key3 => $value3) {
		// 						$match->$key3 = $value3;
		// 					}
		// 					$this->matches[$key2] = $match;
		// 				}
		// 			} else {
		// 				$this->$key = $value;
		// 			}
		// 		}
		// 		break;
		// 	case 'timeline':
		// 		// 2 itterations 
		// 		foreach ($data as $key => $value) {
		// 			if (property_exists($this, $key)) {
		// 				if ($key == "frameInterval") {
		// 					$this->frameInterval = $value;
		// 				} else {
		// 					// x itterations ParticipantFrame / Events
		// 					foreach ($value as $key2 => $value2) {
		// 						$matchTimelineFrame = new matchTimelineFrame();
		// 						$this->frames[$key2] = $matchTimelineFrame;
		// 						// participantFrames/ Event / Timestamp
		// 						foreach ($value2 as $key3 => $value3) {

		// 							if ($key3 == "participantFrames") {
		// 								foreach ($value3 as $key4 => $value4) {

		// 									$participantDetails = new matchTimelineParticipantDetails();

		// 									foreach ($value4 as $key5 => $value5) {
		// 										$participantDetails->$key5 = $value5;

		// 										if ($key5 == "position") {
		// 											$position = new matchTimelinePosition();
		// 											foreach ($value5 as $key6 => $value6) {
		// 												// Assign xand y to object
		// 												$position->$key6 = $value6;
		// 											}
		// 											// Assign position object to match details
		// 											$participantDetails->$key5 = $position;
		// 										} else {
		// 											// Assign the rest of the properties to object
		// 											$participantDetails->$key5 = $value5;
		// 										}
		// 									}
		// 									$matchTimelineFrame->participantFrames[$key4] = $participantDetails;
		// 								}
		// 							} elseif ($key3 == "events") {
		// 								foreach ($value3 as $key4 => $value4) {
		// 									$events = new matchTimelineEvent();
		// 									foreach ($value4 as $key5 => $value5) {
		// 										if ($key5 == "position") {
		// 											$position = new matchTimelinePosition();
		// 											foreach ($value5 as $key6 => $value6) {
		// 												// Assign xand y to object
		// 												$position->$key6 = $value6;
		// 											}
		// 											$events->$key5 = $position;
		// 										} else {
		// 											$events->$key5 = $value5;
		// 										}
		// 									}
		// 									$matchTimelineFrame->events[$key4] = $events;
		// 								}
		// 							} elseif ($key3 == "timestamp") {
		// 								$matchTimelineFrame->timestamp = $value3;
		// 							}
		// 						}
		// 					}
		// 				}
		// 			} else { }
		// 		}
		// 		break;
		// 	case 'activeGame':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			if ($key  == "participants") {
		// 				foreach ($value as $key2 => $value2) {
		// 					$participant = new ActiveParticipant();
		// 					foreach ($value2 as $key3 => $value3) {
		// 						if ($key3 == "gameCustomizationObjects") {
		// 							$gameCustomization = new gameCustomizationObjects();
		// 							foreach ($value3 as $key4 => $value4) {
		// 								$gameCustomization->$key4 = $value4;
		// 							}
		// 							$participant->gameCustomizationObjects = $gameCustomization;
		// 						} elseif ($key3 == "perks") {
		// 							$perks = new Perk();
		// 							foreach ($value3 as $key4 => $value4) {
		// 								if ($key4 == "perkIds") {
		// 									foreach ($value4 as $key5 => $value5) {
		// 										$perks->perkIds[$key5] = $value5;
		// 									}
		// 								} else {
		// 									$perks->$key4 = $value4;
		// 								}
		// 							}
		// 							$participant->perks = $perks;
		// 						} else {
		// 							$participant->$key3 = $value3;
		// 						}
		// 						$this->participants[$key2] = $participant;
		// 					}
		// 				}
		// 			} elseif ($key == "observers") {
		// 				$observer = new Observer();
		// 				$observer->encryptionKey = $value["encryptionKey"];
		// 				$this->$key = $observer;
		// 			} elseif ($key == "bannedChampions") {
		// 				foreach ($value as $key2 => $value2) {
		// 					$banActive = new BanActive();
		// 					foreach ($value2 as $key3 => $value3) {
		// 						$banActive->$key3 = $value3;
		// 					}
		// 					$this->$key[$key2] = $banActive;
		// 				}
		// 			} else {
		// 				$this->$key = $value;
		// 			}
		// 		}
		// 		break;
		// 	case 'championMastery':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			$championMasteries = new championMasteries();
		// 			foreach ($value as $key2 => $value2) {
		// 				$championMasteries->$key2 = $value2;
		// 			}
		// 			// Assign object to array with the ammount of champion the summoner has at least some mastery on elements
		// 			$this->championMasteries[$key] = $championMasteries;
		// 		}
		// 		break;
		// 	case 'championMasteryByChampion':
		// 		unset($data["class"]);
		// 		foreach ($data as $key => $value) {
		// 			$this->$key = $value;
		// 		}
		// 		break;
		// 	default:
		// 		die("Unknown class with the name " . $data["class"]);
		// 		break;
		// }
	}
	private static function getIterablePropertyName( string $phpDocComment )
	{
		preg_match('/@iterable\s\$([\w]+)/', $phpDocComment, $matches);
		if (isset($matches[1]))
			return $matches[1];

		return false;
	}
	private static function getLinkablePropertyData( string $phpDocComment )
	{
		preg_match('/@linkable\s(?<function>[\w]+)(?:\(\$(?<parameter>[\w]+)+?\))?/', $phpDocComment, $matches);

		// Filter only named capture groups
		$matches = array_filter($matches, function ($v, $k) { return is_string($k); }, ARRAY_FILTER_USE_BOTH);
		if (@$matches['function'] && @$matches['parameter'])
			return $matches;

		return false;
	}
}
