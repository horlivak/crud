<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Http\Controller;

use App\Core\Doctrine\Exception\DatabaseErrorException;
use App\Core\Exception\DateTimeConverterException;
use App\Domain\Invoice\Dto\InvoiceDto;
use App\Domain\Invoice\Facade\InvoiceFacade;
use App\Domain\Invoice\Form\InvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class InvoiceNewController extends AbstractController
{
	public function __construct(
		private readonly ValidatorInterface $validator,
		private readonly InvoiceFacade $invoiceFacade,
	) {
	}

	/**
	 * @throws LogicException
	 * @throws DatabaseErrorException
	 * @throws DateTimeConverterException
	 */
	#[Route(path: '/new', name: 'invoice_new')]
	public function __invoke(Request $request): Response
	{
		$invoice = new InvoiceDto();
		$form = $this->createForm(type: InvoiceType::class, data: $invoice);
		$form->handleRequest(request: $request);

		if ($form->isSubmitted() && $form->isValid()) {
			$errors = $this->validator->validate(value: $invoice);
			if (count($errors) > 0) {
				return $this->render(view: 'invoice/form.html.twig', parameters: [
					'invoiceForm' => $form,
					'errors' => $errors,
				]);
			}

			$this->invoiceFacade->createInvoiceFromDto(invoiceDto: $invoice);

			return $this->redirectToRoute(route: 'invoice_index');
		}

		return $this->render(view: 'invoice/form.html.twig', parameters: [
			'invoiceForm' => $form,
		]);
	}
}
