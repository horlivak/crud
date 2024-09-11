<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Handler;

use App\Core\Doctrine\Exception\DatabaseErrorException;
use App\Domain\Invoice\Repository\InvoiceRepository;
use Carbon\CarbonImmutable;

final readonly class InvoiceNumberHandler
{
	public function __construct(
		private InvoiceRepository $invoiceRepository,
	) {
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function getInvoiceNumber(): string
	{
		$date = CarbonImmutable::now();
		$invoiceNumber = $this->invoiceRepository->findLastUsableInvoiceNumberByYear(year: (int) $date->format('Y'));

		$invoiceNumberFormatted = str_pad((string) $invoiceNumber, 4, '0', STR_PAD_LEFT);

		return $date->format('Y') . $invoiceNumberFormatted;
	}
}
