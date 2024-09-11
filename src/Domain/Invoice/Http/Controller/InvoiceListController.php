<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Http\Controller;

use App\Domain\Invoice\Facade\InvoiceFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InvoiceListController extends AbstractController
{
	public function __construct(
		private readonly InvoiceFacade $invoiceFacade,
	) {
	}

	#[Route(path: '/', name: 'invoice_index', methods: Request::METHOD_GET)]
	public function __invoke(Request $request): Response
	{
		return $this->render(view: 'invoice/index.html.twig', parameters: [
			'invoices' => $this->invoiceFacade->getInvoicesWithPagination(
				page: $request->query->getInt(key: 'page', default: 1),
				limitPerPage: 10
			),
		]);
	}
}
