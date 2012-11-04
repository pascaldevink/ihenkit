<?php
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// Database configuration
include_once __DIR__.'/config/db-config.php';

$em = $app['orm.em'];

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

$app->boot();