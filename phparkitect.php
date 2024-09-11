<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
	$mvcClassSet = ClassSet::fromDir(directory: __DIR__ . '/src');

	$rules = [];

	// uniform naming rules
	$rules[] = Rule::allClasses()
		->that(expression: new ResideInOneOfTheseNamespaces('App\DataFixtures'))
		->should(expression: new HaveNameMatching(name: '*Fixtures'))
		->because(reason: 'we want uniform naming');

	$config
		->add($mvcClassSet, ...$rules);

	// test rules
	$mvcClassTestSet = ClassSet::fromDir(directory: __DIR__ . '/tests');
	$testRules = [];

	$testRules[] = Rule::allClasses()
		->that(expression: new ResideInOneOfTheseNamespaces('App\Tests\Functional', 'App\Tests\Unit'))
		->should(expression: new HaveNameMatching(name: '*Test'))
		->because(reason: 'we want uniform naming');

	$config
		->add($mvcClassTestSet, ...$testRules);
};
