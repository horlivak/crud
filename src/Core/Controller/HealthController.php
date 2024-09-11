<?php

declare(strict_types=1);

namespace App\Core\Controller;

use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[AsController]
final class HealthController
{
	private ?int $id = null;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	) {
	}

	/**
	 * @throws RandomException
	 * @throws InvalidArgumentException
	 */
	#[Route(path: '/health')]
	public function index(): JsonResponse
	{
		$this->id ??= random_int(1, 999999);

		$db = $this->isDoctrineConnectionLive();

		return new JsonResponse(data: [
			'id' => $this->id,
			'date' => Carbon::now()->format(format: DateTimeInterface::ATOM),
			'api' => true,
			'database' => $db,
		], status: $db ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	private function isDoctrineConnectionLive(): bool
	{
		try {
			$connection = $this->entityManager->getConnection();
			$connection->executeQuery(sql: 'SELECT 1')
				->free();

			return true;
		} catch (Throwable) {
			return false;
		}
	}
}
