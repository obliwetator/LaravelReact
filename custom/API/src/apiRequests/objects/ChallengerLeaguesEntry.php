<?php
//
//  Entry.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 17, 2019

namespace API\LeagueAPI\Objects;
use LeagueAPI\Objects\objectInit;

class ChallengerLeaguesEntry extends objectInit
{
    /** @var string $summonerId */
    public $summonerId;
    /** @var string $summonerName */
    public $summonerName;
    /** @var int $leaguePoints */
    public $leaguePoints;
    /** @var string $rank */
    public $rank;
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
}