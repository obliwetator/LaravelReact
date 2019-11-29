<?php

namespace API\LeagueAPI\Definitions;

interface IPlatform
{
	/**
	 *   Returns platform list by region.
	 *
	 * @return array
	 */
	public function getList(): array;

	/**
	 *   Returns platform name based on region identifier (can either be string or internal numeric ID).
	 *
	 * @param string $region
	 *
	 * @return string
	 */
	public function getPlatformName( string $region ): string;
}