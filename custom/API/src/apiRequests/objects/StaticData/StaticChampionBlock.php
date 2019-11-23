<?php
//
//  StaticChampionBlock.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 12, 2019

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticChampionBlock extends objectInit
{
    /** @var string $type */
    public $type;
    /** @var bool $recMath */
    public $recMath;
    /** @var int $minSummonerLevel */
    public $minSummonerLevel;
    /** @var int $maxSummonerLevel */
    public $maxSummonerLevel;
    /** @var string $showIfSummonerSpell */
    public $showIfSummonerSpell;
    /** @var string $hideIfSummonerSpell */
    public $hideIfSummonerSpell;
    /** @var StaticChampionItem[] $items */
	public $items;
}