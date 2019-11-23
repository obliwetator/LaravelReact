<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticChampionSpell extends objectInit
{
	/** @var string $cooldownBurn */
	public $cooldownBurn;
	/** @var string $resource */
	public $resource;
	/** @var StaticLevelTip $leveltip */
	public $leveltip;
	/** @var StaticSpellVars[] $vars */
	public $vars;
	/** @var string $costType */
	public $costType;
	/** @var StaticImage $image */
	public $image;
	/**
	 *   This field is a List of List of Double.
	 *
	 * @var int[][] $effect
	 */
	public $effect;
	/** @var string $tooltip */
	public $tooltip;
	/** @var int $maxrank */
	public $maxrank;
	/** @var string $costBurn */
	public $costBurn;
	/** @var string $rangeBurn */
	public $rangeBurn;
	/**
	 *   This field is either a List of Integer or the String 'self' for spells 
	 * that target one's own champion.
	 *
	 * @var int[] $range
	 */
	public $range;
	/** @var double[] $cooldown */
	public $cooldown;
	/** @var int[] $cost */
	public $cost;
	/** @var string $id */
	public $id;
	/** @var string $description */
	public $description;
	/** @var string[] $effectBurn */
	public $effectBurn;
	/** @var string $name */
	public $name;
}