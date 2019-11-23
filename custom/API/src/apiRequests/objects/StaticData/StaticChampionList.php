<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticChampionList extends objectInit
{
	/** @var string[] $keys */
	public $keys;
	/** @var StaticChampion[] $data */
	public $data;
	/** @var string $version */
	public $version;
	/** @var string $type */
	public $type;
	/** @var string $format */
	public $format;
}