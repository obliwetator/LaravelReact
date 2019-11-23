<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticGold extends objectInit
{
	/** @var int $sell */
	public $sell;
	/** @var int $total */
	public $total;
	/** @var int $base */
	public $base;
	/** @var bool $purchasable */
	public $purchasable;
}