<?php
//
//  ChallengerLeagues.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 17, 2019

namespace API\LeagueAPI\Objects;
use LeagueAPI\Objects\objectInit;

class ChallengerLeagues extends objectInit
{
    /** @var string $tier */
    public $tier;
	/** @var string $leagueId */
    public $leagueId;
    /** @var string $queue */
    public $queue;
    /** @var string $name */
    public $name;
    /** @var ChallengerLeaguesEntry[] $entries */
    public $entries;
}