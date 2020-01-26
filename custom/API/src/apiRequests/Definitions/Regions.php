<?php

/**
 * Copyright (C) 2016-2019  Daniel Dolejška
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace API\LeagueAPI\Definitions;

class Region implements IRegion
{
	// ==================================================================dd=
	//     Standard game regions (servers)
	// ==================================================================dd=

	const NORTH_AMERICA = 'na';

	const EUROPE_WEST = 'euw';

	const EUROPE_EAST = 'eune';

	const LAMERICA_SOUTH = 'las';

	const LAMERICA_NORTH = 'lan';

	const BRASIL = 'br';

	const RUSSIA = 'ru';

	const TURKEY = 'tr';

	const OCEANIA = 'oce';

	const KOREA = 'kr';

	const JAPAN = 'jp';

	public static $list = array(
		Platform::NORTH_AMERICA   => self::NORTH_AMERICA,
		Platform::EUROPE          => self::EUROPE,
		Platform::EUROPE_WEST     => self::EUROPE_WEST,
		Platform::EUROPE_EAST     => self::EUROPE_EAST,
		Platform::LAMERICA_SOUTH  => self::LAMERICA_SOUTH,
		Platform::LAMERICA_NORTH  => self::LAMERICA_NORTH,
		Platform::BRASIL          => self::BRASIL,
		Platform::RUSSIA          => self::RUSSIA,
		Platform::TURKEY          => self::TURKEY,
		Platform::OCEANIA         => self::OCEANIA,
		Platform::ASIA            => self::ASIA,
		Platform::KOREA           => self::KOREA,
		Platform::JAPAN           => self::JAPAN,
		Platform::AMERICAS        => self::AMERICAS,
	);


	// ==================================================================dd=
	//     Control functions
	// ==================================================================dd=

	public function getList(): array
	{
		return self::$list;
	}

	public function getRegionName( string $region ): string
	{
		$region = strtolower($region);

		return self::$list[$region];
	}
}