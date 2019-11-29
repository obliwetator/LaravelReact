<?php

namespace API\LeagueAPI\Definitions;

interface IRegion
{
	const EUROPE = 'europe';

	const AMERICAS = 'americas';

	const ASIA = 'asia';

	/**
	 *   Returns region list.
	 *
	 * @return array
	 */
	public function getList(): array;

	/**
	 *   Returns region name based on region identifier (can either be string or internal numeric ID).
	 *
	 * @param string $region
	 *
	 * @return string
	 */
	public function getRegionName( string $region ): string;
}