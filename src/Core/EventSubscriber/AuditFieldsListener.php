<?php

declare(strict_types=1);

namespace App\Core\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate)]
final class AuditFieldsListener
{
	public function prePersist(PrePersistEventArgs $args): void
	{
		$entity = $args->getObject();
		if (!method_exists($entity, 'createdTimestamps')) {
			return;
		}

		$entity->createdTimestamps();
	}

	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		$this->setUpdatedAt(entity: $entity);
	}

	private function setUpdatedAt(object $entity): void
	{
		if (!method_exists($entity, 'updatedTimestamps')) {
			return;
		}

		$entity->updatedTimestamps();
	}
}
