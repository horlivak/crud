<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class InvoiceItemDto
{
	#[Assert\NotBlank()]
	#[Assert\Length(max: 255)]
	public string $name;

	#[Assert\NotBlank]
	#[Assert\GreaterThan(value: 0)]
	public int $quantity;

	#[Assert\NotBlank]
	#[Assert\GreaterThan(value: 0)]
	public float $unitPrice;
}
