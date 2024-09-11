<?php

declare(strict_types=1);

namespace App\Domain\Invoice\Form;

use App\Domain\Invoice\Dto\InvoiceDto;
use App\Domain\Invoice\Enum\PaymentMethodEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class InvoiceType extends AbstractType
{
	/**
	 * @param mixed[] $options
	 *
	 * @throws ConstraintDefinitionException
	 * @throws InvalidOptionsException
	 * @throws MissingOptionsException
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add(child: 'customer', type: TextType::class, options: [
				'attr' => [
					'class' => 'form-control',
				],
				'constraints' => [new NotBlank()],
				'required' => true,
			])
			->add(child: 'supplier', type: TextType::class, options: [
				'attr' => [
					'class' => 'form-control',
				],
				'constraints' => [new NotBlank()],
				'required' => true,
			])
			->add(child: 'issueDate', type: DateType::class, options: [
				'widget' => 'single_text',
				'input' => 'string',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
				'required' => true,
			])
			->add(child: 'dueDate', type: DateType::class, options: [
				'widget' => 'single_text',
				'input' => 'string',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
				'required' => true,
			])
			->add(child: 'taxDate', type: DateType::class, options: [
				'widget' => 'single_text',
				'input' => 'string',
				'constraints' => [new NotBlank()],
				'attr' => [
					'class' => 'form-control',
				],
				'required' => true,
			])
			->add(child: 'paymentMethod', type: EnumType::class, options: [
				'class' => PaymentMethodEnum::class,
				'required' => true,
				'constraints' => [new NotBlank()],
			])
			->add(child: 'items', type: CollectionType::class, options: [
				'label' => false,
				'entry_type' => InvoiceItemType::class,
				'entry_options' => [
					'label' => false,
				],
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'prototype' => true,
				'prototype_name' => '__name__',
				'attr' => [
					'class' => 'item-collection',
				],
			]);
	}

	/**
	 * @throws AccessException
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(defaults: [
			'data_class' => InvoiceDto::class,
		]);
	}
}
