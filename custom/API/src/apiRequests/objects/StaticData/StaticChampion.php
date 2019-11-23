<?php
//
//  Akali.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on July 12, 2019

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticChampion extends objectInit
{
    /** @var string $id */
    public $id;
    /** @var string $key */
    public $key;
    /** @var string $name */
    public $name;
    /** @var string $title */
    public $title;
    /** @var StaticChampionImage $image */
    public $image;
    /** @var StaticChampionSkin[] $skins */
    public $skins;
    /** @var string $lore */
    public $lore;
    /** @var string $blurb */
    public $blurb;
    /** @var string[] $allytips */
    public $allytips;
    /** @var string[] $enemytips */
    public $enemytips;
    /** @var string[] $tags */
    public $tags;
    /** @var string $partype */
    public $partype;
    /** @var StaticChampionInfo $info */
    public $info;
    /** @var StaticChampionStat $stats */
    public $stats;
    /** @var StaticChampionSpell[] $spells */
    public $spells;
    /** @var StaticChampionPassive $passive */
    public $passive;
    /** @var StaticChampionRecommended[] $recommended */
    public $recommended;
}