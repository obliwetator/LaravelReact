<?php
//
//  Participant.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 12, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class Participant extends objectInit
{
    /** @var int $championId */
    public $championId;
    /** @var string $highestAchievedSeasonTier */
    public $highestAchievedSeasonTier;
    /** @var int $participantId */
    public $participantId;
    /** @var int $spell1Id */
    public $spell1Id;
    /** @var int $spell2Id */
    public $spell2Id;
    /** @var Stat $stats */
    public $stats;
    /** @var int $teamId */
    public $teamId;
    /** @var Timeline $timeline */
    public $timeline;
}
