<?php
//
//  matchTimelineFrame.php
//  Model Generated using http://www.jsoncafe.com/ 
//  Created on June 17, 2019

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class matchTimelineFrame extends objectInit
{
	/** @var matchTimelineParticipantDetails[] $participantFrames*/
    public $participantFrames;
	/** @var matchTimelineEvent[] $events */
    public $events;
	/** @var int $timestamp*/
    public $timestamp;
}
