<?php

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class championMasteriesByChampion extends objectInit
{
	/** @var Integer $championId */
    public $championId;
	/** @var Integer $championLevel */
    public $championLevel;
	/** @var Integer $championPoints */
    public $championPoints;
	/** @var Integer $lastPlayTime */
    public $lastPlayTime;
	/** @var Integer $championPointsSinceLastLevel */
    public $championPointsSinceLastLevel;
	/** @var Integer $championPointsUntilNextLevel */
    public $championPointsUntilNextLevel;
	/** @var Boolean $chestGranted */
    public $chestGranted;
	/** @var Integer $tokensEarned */
    public $tokensEarned;
	/** @var String $summonerId */
    public $summonerId;
}