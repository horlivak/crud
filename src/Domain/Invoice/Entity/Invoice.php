<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Entity;

use App\Core\Doctrine\Traits\ModificationDateTimeTrait;
use App\Core\Doctrine\Traits\UuidTrait;
use App\Core\Exception\DateTimeConverterException;
use App\Core\Tool\DateTimeConverter;
use App\Domain\Invoice\Dto\InvoiceDto;
use App\Domain\Invoice\Enum\PaymentMethodEnum;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Invoice
{
	use ModificationDateTimeTrait;
	use UuidTrait;

	#[ORM\Column(type: 'string', length: 255)]
	private string $customer;

	#[ORM\Column(type: 'string', length: 255)]
	private string $supplier;

	#[ORM\Column(type: 'carbon_immutable')]
	private CarbonImmutable $issueDate;

	#[ORM\Column(type: 'carbon_immutable')]
	private CarbonImmutable $dueDate;

	#[ORM\Column(type: 'carbon_immutable')]
	private CarbonImmutable $taxDate;

	#[ORM\Column(type: 'string', enumType: PaymentMethodEnum::class)]
	private PaymentMethodEnum $paymentMethod;

	#[ORM\Column(type: 'string', length: 10)]
	private string $invoiceNumber;

	/**
	 * @var Collection<int, InvoiceItem>
	 */
	#[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItem::class, cascade: ['persist', 'remove'])]
	private Collection $items;

	public function __construct(
		string $supplier,
		string $customer,
		CarbonImmutable $issueDate,
		CarbonImmutable $dueDate,
		CarbonImmutable $taxDate,
		PaymentMethodEnum $paymentMethod,
		string $invoiceNumber
	) {
		$this->id = Uuid::v4();
		$this->supplier = $supplier;
		$this->customer = $customer;
		$this->issueDate = $issueDate;
		$this->dueDate = $dueDate;
		$this->taxDate = $taxDate;
		$this->paymentMethod = $paymentMethod;
		$this->invoiceNumber = $invoiceNumber;
		$this->items = new ArrayCollection();
	}

	public function getCustomer(): string
	{
		return $this->customer;
	}

	public function getSupplier(): string
	{
		return $this->supplier;
	}

	public function getIssueDate(): CarbonImmutable
	{
		return $this->issueDate;
	}

	public function getDueDate(): CarbonImmutable
	{
		return $this->dueDate;
	}

	public function getTaxDate(): CarbonImmutable
	{
		return $this->taxDate;
	}

	public function getPaymentMethod(): PaymentMethodEnum
	{
		return $this->paymentMethod;
	}

	public function getInvoiceNumber(): string
	{
		return $this->invoiceNumber;
	}

	/**
	 * @return Collection<int, InvoiceItem>
	 */
	public function getItems(): Collection
	{
		return $this->items;
	}

	public function addInvoiceItem(InvoiceItem $invoiceItem): void
	{
		if (!$this->items->contains($invoiceItem)) {
			$this->items->add($invoiceItem);
		}
	}

	public function getTotalSum(): float
	{
		$total = 0;

		foreach ($this->items as $item) {
			$total += $item->getTotal();
		}

		return $total;
	}

	/**
	 * @throws DateTimeConverterException
	 */
	public function updateFromDto(InvoiceDto $invoiceDto): void
	{
		$this->taxDate = DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->taxDate);
		$this->dueDate = DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->dueDate);
		$this->issueDate = DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->issueDate);
		$this->paymentMethod = $invoiceDto->paymentMethod;
		$this->customer = $invoiceDto->customer;
		$this->supplier = $invoiceDto->supplier;
	}

	/**
	 * @throws DateTimeConverterException
	 */
	public static function createFromDto(InvoiceDto $invoiceDto, string $invoiceNumber): self
	{
		return new self(
			supplier: $invoiceDto->supplier,
			customer: $invoiceDto->customer,
			issueDate: DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->issueDate),
			dueDate: DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->dueDate),
			taxDate: DateTimeConverter::createFormatedDateFromString(dateTimeString: $invoiceDto->taxDate),
			paymentMethod: $invoiceDto->paymentMethod,
			invoiceNumber: $invoiceNumber,
		);
	}
}
