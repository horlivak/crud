<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Facade;

use App\Core\Doctrine\Exception\DatabaseErrorException;
use App\Core\Doctrine\PersistenceManager;
use App\Core\Exception\DateTimeConverterException;
use App\Domain\Invoice\Dto\InvoiceDto;
use App\Domain\Invoice\Entity\Invoice;
use App\Domain\Invoice\Entity\InvoiceItem;
use App\Domain\Invoice\Handler\InvoiceNumberHandler;
use App\Domain\Invoice\Repository\InvoiceRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final readonly class InvoiceFacade
{
	public function __construct(
		private InvoiceRepository $invoiceRepository,
		private PaginatorInterface $paginator,
		private PersistenceManager $persistenceManager,
		private InvoiceNumberHandler $invoiceNumberHandler,
	) {
	}

	/**
	 * @return PaginationInterface<int, mixed>
	 */
	public function getInvoicesWithPagination(int $page, int $limitPerPage): PaginationInterface
	{
		return $this->paginator->paginate(
			target: $this->invoiceRepository->getQueryForPagination(),
			page: $page,
			limit: $limitPerPage,
		);
	}

	/**
	 * @throws DatabaseErrorException
	 * @throws DateTimeConverterException
	 */
	public function createInvoiceFromDto(InvoiceDto $invoiceDto): Invoice
	{
		$invoice = Invoice::createFromDto(
			invoiceDto: $invoiceDto,
			invoiceNumber: $this->invoiceNumberHandler->getInvoiceNumber()
		);

		foreach ($invoiceDto->items as $item) {
			$invoice->addInvoiceItem(invoiceItem: InvoiceItem::createFromDto(invoiceItemDto: $item, invoice: $invoice));
		}

		$this->persistenceManager->persist(entity: $invoice);
		$this->persistenceManager->flush();

		return $invoice;
	}

	/**
	 * @throws DatabaseErrorException
	 * @throws DateTimeConverterException
	 */
	public function updateInvoiceFromDto(InvoiceDto $invoiceDto, Invoice $invoice): Invoice
	{
		$invoice->updateFromDto(invoiceDto: $invoiceDto);

		foreach ($invoice->getItems() as $item) {
			$this->persistenceManager->remove($item);
		}

		foreach ($invoiceDto->items as $item) {
			$invoice->addInvoiceItem(invoiceItem: InvoiceItem::createFromDto(invoiceItemDto: $item, invoice: $invoice));
		}

		$this->persistenceManager->flush();

		return $invoice;
	}
}
