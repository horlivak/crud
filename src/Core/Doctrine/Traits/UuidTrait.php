<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
	#[ORM\Id]
	#[ORM\Column(type: 'uuid', unique: true, nullable: false)]
	private Uuid $id;

	final public function getId(): Uuid
	{
		return $this->id;
	}

	final public function getIdString(): string
	{
		return $this->id->toRfc4122();
	}
}
