<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Form;

use App\Domain\Invoice\Dto\InvoiceItemDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class InvoiceItemType extends AbstractType
{
	/**
	 * @throws InvalidOptionsException
	 * @throws MissingOptionsException
	 * @throws ConstraintDefinitionException
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add(child: 'name', type: TextType::class, options: [
				'label' => 'Název položky',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
			])
			->add(child: 'quantity', type: IntegerType::class, options: [
				'label' => 'Počet jednotek',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
			])
			->add(child: 'unitPrice', type: MoneyType::class, options: [
				'label' => 'Jednotková cena',
				'currency' => 'CZK',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
			]);
	}

	/**
	 * @throws AccessException
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(defaults: [
			'data_class' => InvoiceItemDto::class,
		]);
	}
}
