<?php

namespace API\LeagueAPI\Objects;
use API\LeagueAPI\Objects\objectInit;

class Summoner extends objectInit
{
	/** @var string $id */
	public $id;
	/** @var string $accountId */
	public $accountId;
	/** @var string $puuid */
	public $puuid;
	/** @var string $name */
	public $name;
	/** @var int $profileIconId */
	public $profileIconId;
	/** @var int $revisionDate */
	public $revisionDate;
	/** @var int $summonerLevel */
	public $summonerLevel;
	/** @var string $trimmedName */
	public $trimmedName;


	public function nameInputSanitization($name)
	{
		// remove spaces
		$name = str_replace(' ', '', $name);

		// Remove HTML special Characters
		$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
		
		// remove spaces
		$name = str_replace(' ', '', $name);

		// Remove HTML special Characters
		$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);

		// Remove horizontal Tab(&#9) control characters
		// TODO: find better regex to replace control characters none worked. this is a workaround only for this chaarcter
		// $name = str_replace("&#9;", '', $name);


		// Remove any character except [0-9], p{L} (a-Z),_,\,.
		// This Regex replaces UTF-8(?) chracters like é etc. API request will throw 400 error.
		// FIX: regex string
		// $name = (preg_replace('/[^0-9\\p{L}_\\.]/',"" ,$name));

		$this->name; 
		$this->trimmedName;
	}

	private function nameInputValidation($name)
	{
		//Check if the name comply with Riot's regex and return boolean value
		// FIX: Same as nameInputSanitization() function
		// $validated = (preg_match('/^[0-9\\p{L}_\\.]{4,16}+$/', $name));

		// Supposedly regular expression to check for UTF-8 characters and limit input to 4-16 characters
		$validated = preg_match('/[0-9\\s\\p{L}]{4,16}+$/u', $name);

		return $validated;
	}
}