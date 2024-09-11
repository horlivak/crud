<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Invoice\Form;

use App\Domain\Invoice\Dto\InvoiceItemDto;
use App\Domain\Invoice\Form\InvoiceItemType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

final class InvoiceItemTypeTest extends TypeTestCase
{
	protected function getExtensions(): array
	{
		$validator = Validation::createValidator();

		return [new ValidatorExtension(validator: $validator)];
	}

	public function testSubmitValidData(): void
	{
		$formData = [
			'name' => 'Test item',
			'quantity' => 2,
			'unitPrice' => 100,
		];

		$model = new InvoiceItemDto();
		// Pass the data to the form directly.
		$form = $this->factory->create(type: InvoiceItemType::class, data: $model);

		// Simulate form submission.
		$form->submit(submittedData: $formData);

		// Check that the form does not have errors.
		self::assertTrue(condition: $form->isSynchronized());

		// Check that the data matches the expected result.
		$expected = new InvoiceItemDto();
		$expected->name = 'Test item';
		$expected->quantity = 2;
		$expected->unitPrice = 100;

		self::assertEquals(expected: $expected, actual: $model);

		// Check the form's fields and structure.
		$view = $form->createView();
		$children = $view->children;

		self::assertArrayHasKey(key: 'name', array: $children);
		self::assertArrayHasKey(key: 'quantity', array: $children);
		self::assertArrayHasKey(key: 'unitPrice', array: $children);
	}

	public function testEmptyDataValidation(): void
	{
		$form = $this->factory->create(type: InvoiceItemType::class);

		// Submit empty data to trigger validation.
		$form->submit([
			'name' => '',
			'quantity' => '',
			'unitPrice' => '',
		]);

		self::assertFalse(condition: $form->isValid());
		$errors = $form->getErrors(deep: true);

		self::assertCount(expectedCount: 3, haystack: $errors); // We expect 3 errors (one for each NotBlank constraint)
	}
}
