<?php
//
//  StaticChampionSpell.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 12, 2019

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticSummonerSpell extends objectInit
{
	/** @var int $id */
	public $id;
	/** @var string $name */
	public $name;
	/** @var string $description */
	public $description;
	/** @var string $tooltip */
	public $tooltip;
	/** @var int $maxrank */
	public $maxrank;
	/** @var double[] $cooldown */
	public $cooldown;
	/** @var string $cooldownBurn */
	public $cooldownBurn;
	/** @var int[] $cost */
	public $cost;
	/** @var string $costBurn */
	public $costBurn;
	/**
	 *   This field is a List of List of Double.
	 *
	 * @var int[][] $effect
	 */
	public $effect;
	/** @var StaticSpellVars[] $vars */
	public $vars;
	/** @var StaticImage $image */
	public $image;
	/** @var string[] $effectBurn */
	public $effectBurn;
	/** @var string $rangeBurn */
	public $rangeBurn;
	/** @var string $key */
	public $key;
	/** @var StaticLevelTip $leveltip */
	public $leveltip;
	/** @var string[] $modes */
	public $modes;
	/** @var string $resource */
	public $resource;
	/** @var string $costType */
	public $costType;
	/**
	 *   This field is either a List of Integer or the String 'self' for spells 
	 * that target one's own champion.
	 *
	 * @var int[] $range
	 */
	public $range;
	/** @var int $summonerLevel */
	public $summonerLevel;
}