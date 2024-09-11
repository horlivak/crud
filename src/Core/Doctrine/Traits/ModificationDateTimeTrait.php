<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Traits;

use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;

trait ModificationDateTimeTrait
{
	#[ORM\Column(type: 'carbon_immutable')]
	private CarbonImmutable $createdAt;

	#[ORM\Column(type: 'carbon_immutable', nullable: true)]
	private ?CarbonImmutable $updatedAt = null;

	final public function updatedTimestamps(): void
	{
		$this->updatedAt = CarbonImmutable::now();
	}

	final public function createdTimestamps(): void
	{
		$this->createdAt = CarbonImmutable::now();
	}

	final public function getCreatedAt(): CarbonImmutable
	{
		return $this->createdAt;
	}

	final public function getUpdatedAt(): ?CarbonImmutable
	{
		return $this->updatedAt;
	}
}
