<?php
//
//  ActiveGame.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 21, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class ActiveGame extends objectInit
{
	/** @var Integer $gameId */
    public $gameId;
    /** @var Integer $mapId */
    public $mapId;
    /** @var String $gameMode */
    public $gameMode;
    /** @var String $gameType */
    public $gameType;
    /** @var Integer $gameQueueConfigId */
    public $gameQueueConfigId;
    /** @var ActiveParticipant[] $participants */
    public $participants;
    /** @var Observer $observers */
    public $observers;
    /** @var String $platformId */
    public $platformId;
    /** @var BanActive[] $bannedChampions */
	public $bannedChampions;
    /** @var Integer $gameStartTime */
    public $gameStartTime;
	/** @var Integer $gameLength */
	public $gameLength;
}