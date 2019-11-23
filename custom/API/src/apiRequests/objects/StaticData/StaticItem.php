<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticItem extends objectInit
{
	/** @var string $name */
	public $name;
	/** @var string $description */
	public $description;
	/** @var string $colloq */
	public $colloq;
	/** @var string $plaintext */
	public $plaintext;
	/** @var bool $consumed */
	public $consumed;
	/** @var bool $consumeOnFull */
	public $consumeOnFull;
	/** @var string[] $into */
	public $into;
	/** @var StaticImage $image */
	public $image;
	/** @var StaticGold $gold */
	public $gold;
	/** @var string[] $tags */
	public $tags;
	/** @var bool[] $maps */
	public $maps;
	/** @var StaticInventoryDataStats $stats */
	public $stats;
	/** @var bool $hideFromAll */
	public $hideFromAll;
	/** @var bool $inStore */
	public $inStore;
	/** @var int $id */
	public $id;
	/** @var int $specialRecipe */
	public $specialRecipe;
	/** @var string[] $effect */
	public $effect;
	/** @var string $requiredChampion */
	public $requiredChampion;
	/** @var string[] $from */
	public $from;
	/** @var string $group */
	public $group;
	/** @var int $depth */
	public $depth;
	/** @var int $stacks */
	public $stacks;
}