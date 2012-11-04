<?php

/**
 * @var $app Silex\Application
 */

$host = getenv('MYSQL_DB_HOST');
$username = getenv('MYSQL_USERNAME');
$password = getenv('MYSQL_PASSWORD');
$db = getenv('MYSQL_DB_NAME');

if (!$host)
{
	$host = 'localhost';
	$username = 'root';
	$password = '';
	$db = 'ihenkit';
}

$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
		'driver'	=> 'pdo_mysql',
		'host'		=> $host, //'localhost',
		'dbname'	=> $db, //'ihenkit',
		'user'		=> $username, //'root',
		'password'	=> $password, //'',
	),
));

$app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
	"orm.proxies_dir" => "../cache/proxies",
	"orm.em.options" => array(
		"mappings" => array(
			array(
				"type" => "annotation",
				"path" => __DIR__."/../src/Tweakers/IHenkIt/Entity",
				"namespace" => "Tweakers\IHenkIt\Entity",
			),
		),
	),
));