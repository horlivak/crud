<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Dto;

use App\Domain\Invoice\Entity\Invoice;
use App\Domain\Invoice\Enum\PaymentMethodEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class InvoiceDto
{
	#[Assert\NotBlank()]
	#[Assert\Length(max: 255)]
	public string $supplier;

	#[Assert\NotBlank]
	#[Assert\Length(max: 255)]
	public string $customer;

	#[Assert\NotBlank]
	#[Assert\Date]
	public string $issueDate;

	#[Assert\NotBlank]
	#[Assert\Date]
	public string $dueDate;

	#[Assert\NotBlank]
	#[Assert\Date]
	public string $taxDate;

	#[Assert\Choice(choices: [PaymentMethodEnum::CACHE, PaymentMethodEnum::BANK_TRANSFER])]
	public PaymentMethodEnum $paymentMethod;

	/**
	 * @var InvoiceItemDto[]
	 */
	public array $items = [];

	public static function createFromEntity(Invoice $invoice): self
	{
		$invoiceDto = new self();
		$invoiceDto->supplier = $invoice->getSupplier();
		$invoiceDto->customer = $invoice->getCustomer();
		$invoiceDto->issueDate = $invoice->getIssueDate()
			->format(format: 'Y-m-d');
		$invoiceDto->dueDate = $invoice->getDueDate()
			->format(format: 'Y-m-d');
		$invoiceDto->taxDate = $invoice->getTaxDate()
			->format(format: 'Y-m-d');
		$invoiceDto->paymentMethod = $invoice->getPaymentMethod();

		$items = [];

		foreach ($invoice->getItems() as $item) {
			$invoiceItemDto = new InvoiceItemDto();
			$invoiceItemDto->name = $item->getName();
			$invoiceItemDto->quantity = $item->getQuantity();
			$invoiceItemDto->unitPrice = $item->getUnitPrice();

			$items[] = $invoiceItemDto;
		}

		$invoiceDto->items = $items;

		return $invoiceDto;
	}
}
