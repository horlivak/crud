<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Invoice\Form;

use App\Domain\Invoice\Dto\InvoiceDto;
use App\Domain\Invoice\Dto\InvoiceItemDto;
use App\Domain\Invoice\Enum\PaymentMethodEnum;
use App\Domain\Invoice\Form\InvoiceType;
use Exception;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

final class InvoiceTypeTest extends TypeTestCase
{
	protected function getExtensions(): array
	{
		$validator = Validation::createValidator();

		return [new ValidatorExtension(validator: $validator)];
	}

	/**
	 * @throws Exception
	 */
	public function testSubmitValidData(): void
	{
		$formData = [
			'customer' => 'John Doe',
			'supplier' => 'ACME Corporation',
			'issueDate' => '2023-09-15',
			'dueDate' => '2023-10-15',
			'taxDate' => '2023-09-20',
			'paymentMethod' => PaymentMethodEnum::BANK_TRANSFER->value,
			'items' => [
				[
					'name' => 'Test item 1',
					'quantity' => 2,
					'unitPrice' => 100,
				],
			],
		];

		$model = new InvoiceDto();
		// Create the form and pass the model data.
		$form = $this->factory->create(type: InvoiceType::class, data: $model);

		// Submit the form data.
		$form->submit(submittedData: $formData);

		// Ensure the form is synchronized (meaning data binding was successful).
		self::assertTrue(condition: $form->isSynchronized());

		// Check that the model's data matches the form submission.
		$expected = new InvoiceDto();
		$expected->customer = 'John Doe';
		$expected->supplier = 'ACME Corporation';
		$expected->issueDate = '2023-09-15';
		$expected->dueDate = '2023-10-15';
		$expected->taxDate = '2023-09-20';
		$expected->paymentMethod = PaymentMethodEnum::BANK_TRANSFER;

		$item = new InvoiceItemDto();
		$item->name = 'Test item 1';
		$item->quantity = 2;
		$item->unitPrice = 100;
		$expected->items = [$item];

		// Compare the expected and actual models.
		self::assertEquals(expected: $expected, actual: $model);

		// Check the form's fields and structure.
		$view = $form->createView();
		$children = $view->children;

		self::assertArrayHasKey(key: 'customer', array: $children);
		self::assertArrayHasKey(key: 'supplier', array: $children);
		self::assertArrayHasKey(key: 'issueDate', array: $children);
		self::assertArrayHasKey(key: 'dueDate', array: $children);
		self::assertArrayHasKey(key: 'taxDate', array: $children);
		self::assertArrayHasKey(key: 'paymentMethod', array: $children);
		self::assertArrayHasKey(key: 'items', array: $children);
	}

	/**
	 * @throws Exception
	 */
	public function testEmptyDataValidation(): void
	{
		$model = new InvoiceDto();
		// Create the form and pass the model data.
		$form = $this->factory->create(type: InvoiceType::class, data: $model);

		// Submit empty data to trigger validation.
		$form->submit(submittedData: [
			'customer' => 1,
			'supplier' => '',
			'dueDate' => '',
			'taxDate' => '',
			'items' => [],
		]);

		// Check that the form is not valid (because some fields should be required).
		self::assertFalse(condition: $form->isValid());

		// Get errors from the form.
		$errors = $form->getErrors(deep: true);

		// Check that there are validation errors.
		self::assertGreaterThan(expected: 0, actual: $errors->count());
	}
}
