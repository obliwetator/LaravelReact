<?php

require '../../../../../../API/vendor/autoload.php';

use Tracy\Debugger;

Debugger::enable();
// Debugger::$strictMode = true;
Debugger::$maxDepth = 10; // default: 3

use API\LeagueAPI\LeagueAPI;
use API\dbCall\dbCall;

// $lol = new LeagueAPI();
// $db = new dbCall();


$region = "eun1";
$locale = "en_GB";
$version = "9.13.1";

$beginIndex = 0;
$endIdex = 100;

$lol = new LeagueAPI();
$db = new dbCall();
// $summoner = $lol->getSummonerName("eun1", "tiltmachine");
// $icons = $lol->getStaticProfileIcons("en_GB", "9.13.1");
// $matchlist = $lol->getMatchlist("eun1", $summoner->accountId, null, null, null, null , null, null, 10);
// $db->setMatchlist("eun1", $matchlist, $summoner->accountId, 0, 5);
// $matchById = $lol->getMatchById("eun1", $matchlist->matches[0]->gameId);

$match = $lol->getMatchById("ready", [222]);

$match[0]->participants[0]->stats->item0;
for ($i; $i < sizeof($match); $i++) { 
	foreach ($match as $key => $value) {
		$name[$key] = $match[$i]->participantIdentities[$key]->player->summonerName;
		$profileIconId[$key] = $match[$i]->participantIdentities[$key]->player->profileIcon;
	}
}
$match[]->

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
	<script src="js.js"></script>
	<title>Document</title>
</head>
<body>
	<div id="comments"></div>

<button>Hey</button>

<input type="button" id="button1" value="CLICK"/>
</body>
<script>

$(document).ready(function(){
	$('#button1').click(function(){
		alert('kfjd');
	});
});

$(function() {
	var $gameItemList = $('GameListContainer');

	$gameItemList.on('click', '.Button.MatchDetail', function(evt){
		evt.preventDefault();

	})
});

$(document).ready(function() {
	$("button").click(function() {
		$("#comments").load("load-comments.php");
	});
});
</script>
</html>