<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Invoice\Entity\Invoice;
use App\Domain\Invoice\Entity\InvoiceItem;
use App\Domain\Invoice\Enum\PaymentMethodEnum;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

final class InvoiceFixtures extends Fixture
{
	public const string INVOICE_TEST = 'invoice_test';

	private Generator $faker;

	public function load(ObjectManager $manager): void
	{
		$this->faker = Factory::create();

		for ($i = 1; $i <= 25; ++$i) {
			$manager->persist($this->createInvoice(supplier: self::INVOICE_TEST . '_' . $i, invoiceNumber: '202000' . $i));
		}

		$manager->flush();
	}

	public function createInvoice(string $supplier, string $invoiceNumber): Invoice
	{
		$invoice = new Invoice(
			supplier: $supplier,
			customer: 'client_' . $supplier,
			issueDate: CarbonImmutable::now(),
			dueDate: CarbonImmutable::now()->addDays(value: 14),
			taxDate: CarbonImmutable::now(),
			paymentMethod: PaymentMethodEnum::CACHE,
			invoiceNumber: $invoiceNumber,
		);

		$invoice->addInvoiceItem(invoiceItem: new InvoiceItem(
			name: $this->faker->name,
			quantity: $this->faker->numberBetween(int1: 1, int2: 10),
			unitPrice: $this->faker->randomFloat(nbMaxDecimals: 1, min: 10, max: 1000),
			invoice: $invoice,
		));

		return $invoice;
	}
}
