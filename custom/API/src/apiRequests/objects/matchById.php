<?php
//
//  MatchById.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 12, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class MatchById extends objectInit
{
    /** @var Integer $gameId */
    public $gameId;
    /** @var String $platformId */
    public $platformId;
    /** @var Integer $gameCreation */
    public $gameCreation;
    /** @var Integer $gameDuration */
    public $gameDuration;
    /** @var Integer $queueId */
    public $queueId;
    /** @var Integer $mapId */
    public $mapId;
    /** @var Integer $seasonId */
    public $seasonId;
    /** @var String $gameVersion */
    public $gameVersion;
    /** @var String $gameMode */
    public $gameMode;
    /** @var String $gameType */
    public $gameType;
    /** @var Team[] $teams */
    public $teams;
    /** @var Participant[] $participants */
    public $participants;
    /** @var ParticipantIdentity[] $participantIdentities */
    public $participantIdentities;
}

