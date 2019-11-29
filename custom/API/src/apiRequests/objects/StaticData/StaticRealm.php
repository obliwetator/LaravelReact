<?php

namespace API\LeagueAPI\Objects\StaticData;
use API\LeagueAPI\Objects\objectInit;

class StaticRealm extends objectInit
{
	/**
	 *   Legacy script mode for IE6 or older.
	 *
	 * @var string $lg
	 */
	public $lg;

	/**
	 *   Latest changed version of Dragon Magic.
	 *
	 * @var string $dd
	 */
	public $dd;

	/**
	 *   Default language for this realm.
	 *
	 * @var string $l
	 */
	public $l;

	/**
	 *   Latest changed version for each data type listed.
	 *
	 * @var string[] $n
	 */
	public $n;

	/**
	 *   Special behavior number identifying the largest profile icon ID that can 
	 * be used under 500. Any profile icon that is requested between this number and 
	 * 500 should be mapped to 0.
	 *
	 * @var int $profileiconmax
	 */
	public $profileiconmax;

	/**
	 *   Additional API data drawn from other sources that may be related to Data 
	 * Dragon functionality.
	 *
	 * @var string $store
	 */
	public $store;

	/**
	 *   Current version of this file for this realm.
	 *
	 * @var string $v
	 */
	public $v;

	/**
	 *   The base CDN URL.
	 *
	 * @var string $cdn
	 */
	public $cdn;

	/**
	 *   Latest changed version of Dragon Magic's CSS file.
	 *
	 * @var string $css
	 */
	public $css;
}
