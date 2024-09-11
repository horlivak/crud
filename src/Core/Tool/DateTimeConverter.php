<?php

declare(strict_types=1);

namespace App\Core\Tool;

use App\Core\Exception\DateTimeConverterException;
use Carbon\CarbonImmutable;
use Throwable;

final class DateTimeConverter
{
	/**
	 * @throws DateTimeConverterException
	 */
	public static function createFormatedDateFromString(string $dateTimeString, string $format = 'Y-m-d'): CarbonImmutable
	{
		try {
			$date = CarbonImmutable::createFromFormat($format, $dateTimeString);
		} catch (Throwable $throwable) {
			throw new DateTimeConverterException($throwable->getMessage(), $throwable->getCode(), $throwable);
		}

		if ($date === false) {
			throw new DateTimeConverterException(message: 'Failed to convert date from format "' . $format . '".');
		}

		return $date;
	}
}
