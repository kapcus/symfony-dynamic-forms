<?php

namespace App\Model;

class AddressModel
{

	const COUNTRY_CZ_CODE = 'CZ';
	const COUNTRY_SK_CODE = 'SK';
	const CITY_PRAGUE_CODE = 'CZ-P';
	const CITY_BRNO_CODE = 'CZ-B';
	const CITY_BRATISLAVA_CODE = 'SK-B';
	const CITY_KOSICE_CODE = 'SK-K';
	const STREET_HIGH_CODE = 'CZ-P-H';

	/**
	 * @return string[]
	 */
	public function getCountries(): array {
		return ['Czech Republic (CZ)' => self::COUNTRY_CZ_CODE, 'Slovakia (SK)' => self::COUNTRY_SK_CODE];
	}

	/**
	 * @return string[]
	 */
	public function getCountryCities(?string $country): array {
		return match ($country) {
			self::COUNTRY_CZ_CODE => ['CZ-Prague' => self::CITY_PRAGUE_CODE, 'CZ-Brno' => self::CITY_BRNO_CODE],
			self::COUNTRY_SK_CODE => ['SK-Bratislava' => self::CITY_BRATISLAVA_CODE, 'SK-Kosice' => self::CITY_KOSICE_CODE],
			default => [],
		};
	}

	/**
	 * @return string[]
	 */
	public function getCityStreets(?string $city): array {
		return match ($city) {
			self::CITY_PRAGUE_CODE => ['CZ-Prague-High' => self::STREET_HIGH_CODE, 'CZ-Prague-Main' => 'CZ-P-M'],
			self::CITY_BRNO_CODE => ['CZ-Brno-New' => 'CZ-B-N', 'CZ-Brno-Old' => 'CZ-B-O'],
			self::CITY_BRATISLAVA_CODE => ['SK-Bratislava-Blue' => 'SK-B-B', 'SK-Bratislava-Red' => 'SK-B-R'],
			self::CITY_KOSICE_CODE => ['SK-Kosice-Stone' => 'SK-K-S', 'SK-Kosice-Wooden' => 'SK-K-W'],
			default => [],
		};
	}

	public function getDefaultCountryCity(?string $country): ?string {
		return match ($country) {
			self::COUNTRY_CZ_CODE => self::CITY_PRAGUE_CODE,
			self::COUNTRY_SK_CODE => self::CITY_BRATISLAVA_CODE,
			default => null
		};
	}
}
