<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\CoversAnnotationWithValueToAttributeRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\Rector\Class_\PreferPHPUnitSelfCallRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector;

return RectorConfig::configure()
	->withPreparedSets(deadCode: true, codeQuality: true, codingStyle: true, typeDeclarations: true)
	->withPaths(
		paths: [
			__DIR__ . '/src',
			__DIR__ . '/tests',
			__DIR__ . '/ecs.php',
			__DIR__ . '/rector.php',
			__DIR__ . '/phparkitect.php',
		]
	)
	->withImportNames()
	->withPreparedSets(
		deadCode: true,
		codeQuality: true,
		codingStyle: true,
		typeDeclarations: true,
		privatization: true,
		instanceOf: true,
		earlyReturn: true,
		strictBooleans: true,
	)
	->withAttributesSets(symfony: true, doctrine: true, phpunit: true, sensiolabs: true)
	->withSets(sets: [
		SetList::CODE_QUALITY,
		SetList::CODING_STYLE,
		SetList::DEAD_CODE,
		SetList::EARLY_RETURN,
		LevelSetList::UP_TO_PHP_82,
		SetList::PRIVATIZATION,
		SetList::TYPE_DECLARATION,
		DoctrineSetList::DOCTRINE_CODE_QUALITY,
		DoctrineSetList::DOCTRINE_COMMON_20,
		DoctrineSetList::DOCTRINE_DBAL_30,
		DoctrineSetList::DOCTRINE_ORM_29,
		SymfonySetList::SYMFONY_60,
		SymfonySetList::SYMFONY_61,
		SymfonySetList::SYMFONY_62,
		SymfonySetList::SYMFONY_CODE_QUALITY,
		PHPUnitSetList::PHPUNIT_CODE_QUALITY,
		PHPUnitSetList::PHPUNIT_100,
		SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
	])
	->withSkip(skip: [
		'tests/bootstrap.php',
		RemoveUselessParamTagRector::class,
		AttributeKeyToClassConstFetchRector::class,
		CoversAnnotationWithValueToAttributeRector::class,
		FlipTypeControlToUseExclusiveTypeRector::class,
		RenameFunctionRector::class,
		PreferPHPUnitThisCallRector::class,
		ClassPropertyAssignToConstructorPromotionRector::class => [__DIR__ . '/src/Domain/*/Entity/*'],
	])
	->withRules(rules: [RestoreDefaultNullToNullableTypePropertyRector::class, PreferPHPUnitSelfCallRector::class]);
