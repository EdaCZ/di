<?php

/**
 * Test: Overriding class of service definition defined in CompilerExtension.
 */

use Nette\DI;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Factory
{
	/** @return Lorem */
	static function createLorem($arg = NULL)
	{
		return new Lorem($arg);
	}
}


class IpsumFactory
{
	/** @return Ipsum */
	static function create($arg = NULL)
	{
		return new Ipsum($arg);
	}
}


class Lorem
{
	function __construct($arg = NULL)
	{
		Notes::add(__METHOD__ . ' ' . $arg);
	}
}

class Ipsum
{
	function __construct($arg = NULL)
	{
		Notes::add(__METHOD__ . ' ' . $arg);
	}
}


class FooExtension extends Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition('one1')
			->setClass('Lorem', [1]);
		$container->addDefinition('one2')
			->setClass('Lorem', [1]);
		$container->addDefinition('one3')
			->setClass('Lorem', [1]);
		$container->addDefinition('one4')
			->setClass('Lorem', [1]);
		$container->addDefinition('one5')
			->setClass('Lorem', [1]);
		$container->addDefinition('one6')
			->setClass('Lorem', [1]);
		$container->addDefinition('one7')
			->setClass('Lorem', [1]);

		$container->addDefinition('two1')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two2')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two3')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two4')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two5')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two6')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('two7')
			->setClass('Lorem')
			->setFactory('Factory::createLorem', [1]);

		$container->addDefinition('three1')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three2')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three3')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three4')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three5')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three6')
			->setFactory('Factory::createLorem', [1]);
		$container->addDefinition('three7')
			->setFactory('Factory::createLorem', [1]);
	}

}


$compiler = new DI\Compiler;
$extension = new FooExtension;
$compiler->addExtension('database', $extension);
$container = createContainer($compiler, 'files/compiler.extensionOverride.neon');


Assert::type(Ipsum::class, $container->getService('one1'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('one2'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('one3'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Lorem::class, $container->getService('one4'));
Assert::same([
	'Lorem::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('one5'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('one6'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('one7'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());


Assert::type(Ipsum::class, $container->getService('two1'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('two2'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('two3'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Lorem::class, $container->getService('two4'));
Assert::same([
	'Lorem::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('two5'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('two6'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('two7'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());


Assert::type(Ipsum::class, $container->getService('three1'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('three2'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('three3'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Lorem::class, $container->getService('three4'));
Assert::same([
	'Lorem::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('three5'));
Assert::same([
	'Ipsum::__construct ',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('three6'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());

Assert::type(Ipsum::class, $container->getService('three7'));
Assert::same([
	'Ipsum::__construct 2',
], Notes::fetch());
