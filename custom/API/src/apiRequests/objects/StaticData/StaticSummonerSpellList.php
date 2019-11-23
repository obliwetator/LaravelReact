<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticSummonerSpellList extends objectInit
{
	/** @var string $version */
	public $version;
	/** @var string $type */
	public $type;
	/** @var StaticSummonerSpell[] $data */
	public $data;
}