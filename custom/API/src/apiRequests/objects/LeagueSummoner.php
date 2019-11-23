<?php
//
//  LeagueSummoner.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 17, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class LeagueSummoner extends objectInit
{
    /** @var string $leagueId */
    public $leagueId;
    /** @var string $queueType */
    public $queueType;
    /** @var string $tier */
    public $tier;
    /** @var string $rank */
    public $rank;
    /** @var string $summonerId */
    public $summonerId;
    /** @var string $summonerName */
    public $summonerName;
    /** @var int $leaguePoints */
    public $leaguePoints;
    /** @var int $wins */
    public $wins;
    /** @var int $losses */
    public $losses;
    /** @var bool $veteran */
    public $veteran;
    /** @var bool $inactive */
    public $inactive;
    /** @var bool $freshBlood */
    public $freshBlood;
    /** @var bool $hotStreak */
	public $hotStreak;
	/** @var miniSeries $miniSeries*/
	public $miniSeries;
}