<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticRunesReforgedPath extends objectInit
{
	/** @var int $id */
	public $id;
	/** @var string $key */
	public $key;
	/** @var string $icon */
	public $icon;
	/** @var string $name */
	public $name;
	/** @var StaticRunesReforgedSlot[] $slots */
	public $slots;
}
