<?php

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class Match extends objectInit
{
	/** @var string $platformId */
	public $platformId; //String
	/** @var int $gameId */
	public $gameId; //Integer
	/** @var int $champion */
	public $champion; //Integer
	/** @var int $queue */
	public $queue; //Integer
	/** @var int $season */
	public $season; //Integer
	/** @var int $timestamp */
	public $timestamp; //Integer
	/** @var string $role */
	public $role; //String
	/** @var string $lane*/
	public $lane; //String
}

class MatchList extends objectInit
{
	/** @var Match[] $matches */
	public $matches; //Match
	/** @var int $totalGames*/
	public $totalGames; //Integer
	/** @var int $endIndex*/
	public $endIndex; //Integer
	/** @var int $startIndex*/
	public $startIndex; //Integer
}


