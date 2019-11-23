<?php
//
//  Team.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 12, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class Team extends objectInit
{
    /** @var int $teamId */
    public $teamId;
    /** @var string $win */
    public $win;
    /** @var bool $firstBlood */
    public $firstBlood;
    /** @var bool $firstTower */
    public $firstTower;
    /** @var bool $firstInhibitor */
    public $firstInhibitor;
    /** @var bool $firstBaron */
    public $firstBaron;
    /** @var bool $firstDragon */
    public $firstDragon;
    /** @var bool $firstRiftHerald */
    public $firstRiftHerald;
    /** @var int $towerKills */
    public $towerKills;
    /** @var int $inhibitorKills */
    public $inhibitorKills;
    /** @var int $baronKills */
    public $baronKills;
    /** @var int $dragonKills */
    public $dragonKills;
    /** @var int $vilemawKills */
    public $vilemawKills;
    /** @var int $riftHeraldKills */
    public $riftHeraldKills;
    /** @var int $dominionVictoryScore */
    public $dominionVictoryScore;
    /** @var Ban[] $bans */
    public $bans;
}