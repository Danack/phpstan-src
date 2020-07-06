<?php declare(strict_types = 1);

namespace PHPStan\Rules\Functions;

use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\FunctionDefinitionCheck;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ExistingClassesInClosureTypehintsRule>
 */
class ExistingClassesInClosureTypehintsRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): \PHPStan\Rules\Rule
	{
		$broker = $this->createReflectionProvider();
		return new ExistingClassesInClosureTypehintsRule(new FunctionDefinitionCheck($broker, new ClassCaseSensitivityCheck($broker), true, false));
	}

	public function testExistingClassInTypehint(): void
	{
		$this->analyse([__DIR__ . '/data/closure-typehints.php'], [
			[
				'Return type of anonymous function has invalid type TestClosureFunctionTypehints\NonexistentClass.',
				10,
			],
			[
				'Parameter $bar of anonymous function has invalid type TestClosureFunctionTypehints\BarFunctionTypehints.',
				15,
			],
			[
				'Class TestClosureFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestClosureFunctionTypehints\fOOfUnctionTypehints.',
				30,
			],
			[
				'Class TestClosureFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestClosureFunctionTypehints\FOOfUnctionTypehintS.',
				30,
			],
			[
				'Parameter $trait of anonymous function has invalid type TestClosureFunctionTypehints\SomeTrait.',
				45,
			],
			[
				'Return type of anonymous function has invalid type TestClosureFunctionTypehints\SomeTrait.',
				50,
			],
		]);
	}

	public function testValidTypehintPhp71(): void
	{
		$this->analyse([__DIR__ . '/data/closure-7.1-typehints.php'], [
			[
				'Parameter $bar of anonymous function has invalid type TestClosureFunctionTypehintsPhp71\NonexistentClass.',
				35,
			],
			[
				'Return type of anonymous function has invalid type TestClosureFunctionTypehintsPhp71\NonexistentClass.',
				35,
			],
		]);
	}

	/**
	 * @requires PHP 7.2
	 */
	public function testValidTypehintPhp72(): void
	{
		$this->analyse([__DIR__ . '/data/closure-7.2-typehints.php'], []);
	}

	public function testVoidParameterTypehint(): void
	{
		if (!self::$useStaticReflectionProvider) {
			$this->markTestSkipped('Test requires static reflection');
		}
		$this->analyse([__DIR__ . '/data/void-parameter-typehint.php'], [
			[
				'Parameter $param of anonymous function has invalid type void.',
				5,
			],
		]);
	}

}
