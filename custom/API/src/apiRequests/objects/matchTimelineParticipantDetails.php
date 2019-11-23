<?php

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class matchTimelineParticipantDetails extends objectInit
{
	/** @var int $participantId */
	public $participantId;
	/** @var matchTimelinePosition $position */
	public $position;
	/** @var int $currentGold */
	public $currentGold;
	/** @var int $totalGold */
	public $totalGold;
	/** @var int $level */
	public $level;
	/** @var int $xp */
	public $xp;
	/** @var int $minionsKilled */
	public $minionsKilled;
	/** @var int $jungleMinionsKilled */
	public $jungleMinionsKilled;
	/** @var int $dominionScore */
	public $dominionScore;
	/** @var int $teamScore */
	public $teamScore;
}
