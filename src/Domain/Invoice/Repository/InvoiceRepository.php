<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Repository;

use App\Core\Doctrine\Exception\DatabaseErrorException;
use App\Domain\Invoice\Entity\Invoice;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Throwable;

final readonly class InvoiceRepository
{
	/**
	 * @var EntityRepository<Invoice>
	 */
	private EntityRepository $entityRepository;

	public function __construct(
		private EntityManagerInterface $entityManager,
	) {
		$this->entityRepository = $this->entityManager->getRepository(Invoice::class);
	}

	public function getQueryForPagination(): Query
	{
		return $this->createQueryBuilder()
			->orderBy(sort: 'invoice.createdAt', order: Order::Descending->value)
			->getQuery();
	}

	/**
	 * @throws DatabaseErrorException
	 */
	public function findLastUsableInvoiceNumberByYear(int $year): int
	{
		try {
			$sql = '
		        SELECT MAX(CAST(SUBSTRING(invoice_number, 5) AS UNSIGNED)) as invoice_number
		        FROM invoice
		        WHERE SUBSTRING(invoice_number, 1, 4) = :year
	        ';

			$stmt = $this->entityManager->getConnection()
				->prepare($sql);
			$stmt->bindValue('year', $year);

			$result = $stmt->executeQuery()
				->fetchOne();

			if (is_int($result) === false) {
				return 1;
			}

			return ++$result;
		} catch (Throwable) {
			throw new DatabaseErrorException();
		}
	}

	private function createQueryBuilder(): QueryBuilder
	{
		return $this->entityRepository->createQueryBuilder(alias: 'invoice');
	}
}
