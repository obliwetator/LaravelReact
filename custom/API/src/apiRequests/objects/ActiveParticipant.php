<?php

//
//  Participant.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 21, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class ActiveParticipant extends objectInit
{
	/** @var Integer $teamId */
	public $teamId;
	/** @var Integer $spell1Id */
	public $spell1Id;
	/** @var Integer $spell2Id */
	public $spell2Id;
	/** @var Integer $championId */
	public $championId;
	/** @var Integer $profileIconId */
	public $profileIconId;
	/** @var String $summonerName */
	public $summonerName;
	/** @var Boolean $bot */
	public $bot;
	/** @var String $summonerId */
	public $summonerId;
	/** @var gameCustomizationObjects $gameCustomizationObjects */
	public $gameCustomizationObjects;
	/** @var Perk $perks */
	public $perks;
}
