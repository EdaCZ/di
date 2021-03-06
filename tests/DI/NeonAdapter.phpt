<?php

/**
 * Test: Nette\DI\Config\Adapters\NeonAdapter
 */

use Nette\DI\Config;
use Nette\DI\Statement;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.neon');


$config = new Config\Loader;
$data = $config->load('files/neonAdapter.neon', 'production');
Assert::same([
	'webname' => 'the example',
	'database' => [
		'adapter' => 'pdo_mysql',
		'params' => [
			'host' => 'db.example.com',
			'username' => 'dbuser',
			'password' => 'secret',
			'dbname' => 'dbname',
		],
	],
], $data);


$data = $config->load('files/neonAdapter.neon', 'development');
Assert::same([
	'webname' => 'the example',
	'database' => [
		'adapter' => 'pdo_mysql',
		'params' => [
			'host' => 'dev.example.com',
			'username' => 'devuser',
			'password' => 'devsecret',
			'dbname' => 'dbname',
		],
	],
	'timeout' => 10,
	'display_errors' => TRUE,
	'html_errors' => FALSE,
	'items' => [10, 20],
	'php' => [
		'zlib.output_compression' => TRUE,
		'date.timezone' => 'Europe/Prague',
	],
], $data);


$config->save($data, TEMP_FILE);
Assert::match(<<<EOD
# generated by Nette

webname: the example
database:
	adapter: pdo_mysql
	params:
		host: dev.example.com
		username: devuser
		password: devsecret
		dbname: dbname

timeout: 10
display_errors: true
html_errors: false
items:
	- 10
	- 20

php:
	zlib.output_compression: true
	date.timezone: Europe/Prague
EOD
, file_get_contents(TEMP_FILE));


$data = $config->load('files/neonAdapter.neon');
$config->save($data, TEMP_FILE);
Assert::match(<<<EOD
# generated by Nette

production:
	webname: the example
	database:
		adapter: pdo_mysql
		params:
			host: db.example.com
			username: dbuser
			password: secret
			dbname: dbname

development < production:
	database:
		params:
			host: dev.example.com
			username: devuser
			password: devsecret

	timeout: 10
	display_errors: true
	html_errors: false
	items:
		- 10
		- 20

	php:
		zlib.output_compression: true
		date.timezone: Europe/Prague

nothing: null
EOD
, file_get_contents(TEMP_FILE));


$data = $config->load('files/neonAdapter.entity.neon');
Assert::equal([
	new Statement('ent', [1]),
	new Statement([
			new Statement('ent', [2]),
			'inner',
		],
		[3, 4]
	),
	new Statement([
			new Statement('ent', [3]),
			'inner',
		],
		[5]
	),
], $data);


$data = $config->load('files/neonAdapter.entity.neon');
$config->save($data, TEMP_FILE);
Assert::match(<<<EOD
# generated by Nette

- ent(1)
- ent(2)::inner(3, 4)
- ent(3)::inner(5)
EOD
, file_get_contents(TEMP_FILE));


$data = $config->load('files/neonAdapter.save.neon');
$config->save($data, TEMP_FILE);
Assert::match(<<<'EOD'
# generated by Nette

parameters:
	class: Ipsum

services:
	referencedService: @one
	referencedServiceWithSetup:
		factory: @one
		setup:
			- $x(10)

	serviceAsParam: Ipsum(@one)
	calledService: @one()
	calledServiceWithArgs: @one(1)
	calledServiceAsParam: Ipsum(@one())
	calledServiceWithArgsAsParam: Ipsum(@one(1))
	one:
		class: %class%
		arguments:
			- 1

	two:
		class: %class%(1)

	three:
		class: Lorem
		create: Factory::createLorem
		arguments:
			- 1

	four:
		create: Factory::createLorem(1)

	five:
		create: [Factory, createLorem](1)

	six: Factory::createLorem(1)
	seven: @factory
	eight: @factory()
	nine:
		- @three
		- foo

	stdClass: stdClass
	factory: Lorem
	rich1: Lorem(1)::foo()
	rich2:
		create: Lorem(Ipsum(@one))::foo(1)

	rich3: Factory::createLorem(1)::foo()
	rich4: Factory()::createLorem(1)::foo()
	0: Lorem(1)::foo()

EOD
, file_get_contents(TEMP_FILE));
