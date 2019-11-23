<?php

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class BanActive extends objectInit
{
	/** @var int $championId */
	public $championId; //Integer
	/** @var int $pickTurn*/
	public $pickTurn; //Integer
	/** @var int $teamId */
	public $teamId;
}