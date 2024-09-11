<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
	->withPreparedSets(
		psr12: true,
		symplify: true,
		arrays: true,
		comments: true,
		docblocks: true,
		spaces: true,
		namespaces: true,
		controlStructures: true,
		phpunit: true,
		strict: true,
		cleanCode: true,
	)
	->withPaths(
		[__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php', __DIR__ . '/phparkitect.php'],
	)
	->withRules([NoUnusedImportsFixer::class, BlankLineBeforeStatementFixer::class])
	->withSets([SetList::DOCTRINE_ANNOTATIONS])
	->withSkip([
		PhpUnitInternalClassFixer::class,
		PhpUnitTestClassRequiresCoversFixer::class,
		GeneralPhpdocAnnotationRemoveFixer::class,
		NotOperatorWithSuccessorSpaceFixer::class,
		PhpUnitStrictFixer::class,
		GlobalNamespaceImportFixer::class,
		PhpdocAlignFixer::class,
		// Custom rules for this project
		NativeFunctionInvocationFixer::class,
		BlankLineBetweenImportGroupsFixer::class,
		NativeConstantInvocationFixer::class,
		MethodArgumentSpaceFixer::class,
		SingleLineThrowFixer::class,
	])
	->withSpacing(Option::INDENTATION_TAB)
	->withPhpCsFixerSets(symfony: true, symfonyRisky: true)
	->withParallel()
	->withConfiguredRule(ConcatSpaceFixer::class, [
		'spacing' => 'one',
	])
	->withConfiguredRule(YodaStyleFixer::class, [
		'equal' => false,
		'identical' => false,
		'less_and_greater' => false,
	]);
