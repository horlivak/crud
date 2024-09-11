<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Entity;

use App\Core\Doctrine\Traits\ModificationDateTimeTrait;
use App\Core\Doctrine\Traits\UuidTrait;
use App\Domain\Invoice\Dto\InvoiceItemDto;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class InvoiceItem
{
	use ModificationDateTimeTrait;
	use UuidTrait;

	#[ORM\Column(type: 'string')]
	private string $name;

	#[ORM\Column(type: 'integer')]
	private int $quantity;

	#[ORM\Column(type: 'float')]
	private float $unitPrice;

	#[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'items')]
	#[ORM\JoinColumn(nullable: false)]
	private Invoice $invoice;

	public function __construct(
		string $name,
		int $quantity,
		float $unitPrice,
		Invoice $invoice
	) {
		$this->id = Uuid::v4();
		$this->name = $name;
		$this->quantity = $quantity;
		$this->unitPrice = $unitPrice;
		$this->invoice = $invoice;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function getUnitPrice(): float
	{
		return $this->unitPrice;
	}

	public function getInvoice(): Invoice
	{
		return $this->invoice;
	}

	public function getTotal(): float
	{
		return $this->quantity * $this->unitPrice;
	}

	public static function createFromDto(InvoiceItemDto $invoiceItemDto, Invoice $invoice): self
	{
		return new self(
			name: $invoiceItemDto->name,
			quantity: $invoiceItemDto->quantity,
			unitPrice: $invoiceItemDto->unitPrice,
			invoice: $invoice,
		);
	}
}
