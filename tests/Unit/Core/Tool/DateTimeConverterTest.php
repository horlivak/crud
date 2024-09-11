<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core\Tool;

use App\Core\Exception\DateTimeConverterException;
use App\Core\Tool\DateTimeConverter;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

final class DateTimeConverterTest extends TestCase
{
	/**
	 * @throws DateTimeConverterException
	 */
	public function testCreateFormattedDateFromStringValidDate(): void
	{
		$dateTimeString = '2023-09-12';
		$expectedDate = CarbonImmutable::createFromFormat('Y-m-d', $dateTimeString);

		$actualDate = DateTimeConverter::createFormatedDateFromString(dateTimeString: $dateTimeString);

		self::assertInstanceOf(expected: CarbonImmutable::class, actual: $actualDate);
		self::assertEquals(expected: $expectedDate, actual: $actualDate);
	}

	/**
	 * @throws DateTimeConverterException
	 */
	public function testCreateFormattedDateFromStringInvalidFormat(): void
	{
		$this->expectException(exception: DateTimeConverterException::class);

		$dateTimeString = '12-09-2023'; // Invalid format for Y-m-d

		DateTimeConverter::createFormatedDateFromString(dateTimeString: $dateTimeString);
	}

	/**
	 * @throws DateTimeConverterException
	 */
	public function testCreateFormattedDateFromStringThrowsException(): void
	{
		$this->expectException(exception: DateTimeConverterException::class);

		// Trigger an exception with an invalid date format
		DateTimeConverter::createFormatedDateFromString(dateTimeString: 'invalid-date');
	}
}
