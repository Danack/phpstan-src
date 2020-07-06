<?php declare(strict_types = 1);

namespace PHPStan\Rules\Functions;

use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\FunctionDefinitionCheck;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ExistingClassesInTypehintsRule>
 */
class ExistingClassesInTypehintsRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): \PHPStan\Rules\Rule
	{
		$broker = $this->createReflectionProvider();
		return new ExistingClassesInTypehintsRule(new FunctionDefinitionCheck($broker, new ClassCaseSensitivityCheck($broker), true, false));
	}

	public function testExistingClassInTypehint(): void
	{
		require_once __DIR__ . '/data/typehints.php';
		$this->analyse([__DIR__ . '/data/typehints.php'], [
			[
				'Return type of function TestFunctionTypehints\foo() has invalid type TestFunctionTypehints\NonexistentClass.',
				15,
			],
			[
				'Parameter $bar of function TestFunctionTypehints\bar() has invalid type TestFunctionTypehints\BarFunctionTypehints.',
				20,
			],
			[
				'Return type of function TestFunctionTypehints\returnParent() has invalid type TestFunctionTypehints\parent.',
				33,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\fOOFunctionTypehints.',
				38,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\fOOFunctionTypehintS.',
				38,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\FOOFunctionTypehints.',
				47,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\FOOFunctionTypehints.',
				47,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\FOOFunctionTypehints.',
				56,
			],
			[
				'Class TestFunctionTypehints\FooFunctionTypehints referenced with incorrect case: TestFunctionTypehints\FOOFunctionTypehints.',
				56,
			],
			[
				'Parameter $trait of function TestFunctionTypehints\referencesTraitsInNative() has invalid type TestFunctionTypehints\SomeTrait.',
				61,
			],
			[
				'Return type of function TestFunctionTypehints\referencesTraitsInNative() has invalid type TestFunctionTypehints\SomeTrait.',
				61,
			],
			[
				'Parameter $trait of function TestFunctionTypehints\referencesTraitsInPhpDoc() has invalid type TestFunctionTypehints\SomeTrait.',
				70,
			],
			[
				'Return type of function TestFunctionTypehints\referencesTraitsInPhpDoc() has invalid type TestFunctionTypehints\SomeTrait.',
				70,
			],
			[
				'Parameter $string of function TestFunctionTypehints\genericClassString() has invalid type TestFunctionTypehints\SomeNonexistentClass.',
				78,
			],
			[
				'Parameter $string of function TestFunctionTypehints\genericTemplateClassString() has invalid type TestFunctionTypehints\SomeNonexistentClass.',
				87,
			],
		]);
	}

	public function testWithoutNamespace(): void
	{
		require_once __DIR__ . '/data/typehintsWithoutNamespace.php';
		$this->analyse([__DIR__ . '/data/typehintsWithoutNamespace.php'], [
			[
				'Return type of function fooWithoutNamespace() has invalid type NonexistentClass.',
				13,
			],
			[
				'Parameter $bar of function barWithoutNamespace() has invalid type BarFunctionTypehints.',
				18,
			],
			[
				'Return type of function returnParentWithoutNamespace() has invalid type parent.',
				31,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: fOOFunctionTypehints.',
				36,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: fOOFunctionTypehintS.',
				36,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: FOOFunctionTypehints.',
				45,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: FOOFunctionTypehints.',
				45,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: FOOFunctionTypehints.',
				54,
			],
			[
				'Class FooFunctionTypehints referenced with incorrect case: FOOFunctionTypehints.',
				54,
			],
			[
				'Parameter $trait of function referencesTraitsInNativeWithoutNamespace() has invalid type SomeTraitWithoutNamespace.',
				59,
			],
			[
				'Return type of function referencesTraitsInNativeWithoutNamespace() has invalid type SomeTraitWithoutNamespace.',
				59,
			],
			[
				'Parameter $trait of function referencesTraitsInPhpDocWithoutNamespace() has invalid type SomeTraitWithoutNamespace.',
				68,
			],
			[
				'Return type of function referencesTraitsInPhpDocWithoutNamespace() has invalid type SomeTraitWithoutNamespace.',
				68,
			],
		]);
	}

	public function testVoidParameterTypehint(): void
	{
		if (!self::$useStaticReflectionProvider) {
			$this->markTestSkipped('Test requires static reflection');
		}
		$this->analyse([__DIR__ . '/data/void-parameter-typehint.php'], [
			[
				'Parameter $param of function VoidParameterTypehint\doFoo() has invalid typehint type void.',
				9,
			],
		]);
	}

}
