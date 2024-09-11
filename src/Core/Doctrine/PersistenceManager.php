<?php

declare(strict_types=1);

namespace App\Core\Doctrine;

use App\Core\Doctrine\Exception\DatabaseErrorException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

final readonly class PersistenceManager
{
	public function __construct(
		/** @var EntityManager $entityManager */
		private EntityManagerInterface $entityManager
	) {
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function persist(object $entity): void
	{
		try {
			$this->entityManager->persist($entity);
		} catch (Throwable $throwable) {
			throw new DatabaseErrorException(
				message: $throwable->getMessage(),
				code: $throwable->getCode(),
				previous: $throwable->getPrevious()
			);
		}
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function remove(object $entity): void
	{
		try {
			$this->entityManager->remove($entity);
		} catch (Throwable $throwable) {
			throw new DatabaseErrorException(
				message: $throwable->getMessage(),
				code: $throwable->getCode(),
				previous: $throwable->getPrevious()
			);
		}
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function flushAndClear(): void
	{
		try {
			$this->entityManager->flush();
			$this->entityManager->clear();
		} catch (Throwable $throwable) {
			throw new DatabaseErrorException(
				message: $throwable->getMessage(),
				code: $throwable->getCode(),
				previous: $throwable
			);
		}
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function clear(): void
	{
		try {
			$this->entityManager->clear();
		} catch (Throwable $throwable) {
			throw new DatabaseErrorException(
				message: $throwable->getMessage(),
				code: $throwable->getCode(),
				previous: $throwable
			);
		}
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function flush(): void
	{
		try {
			$this->entityManager->flush();
		} catch (Throwable $throwable) {
			throw new DatabaseErrorException(
				message: $throwable->getMessage(),
				code: $throwable->getCode(),
				previous: $throwable
			);
		}
	}
}
