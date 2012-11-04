<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Debugging
if ($_GET['userId'] == 266225 || $_POST['userId'] == 266225 || $_GET['trace'] = 1)
	$app['debug'] = true;

// Database configuration
include_once __DIR__.'/../config/db-config.php';

// Caching
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
	'http_cache.cache_dir' => __DIR__.'/../cache/',
));

// Controller resolving overwrite
$app['resolver'] = $app->share(function () use ($app) {
	return new Tweakers\IHenkIt\Controller\ControllerResolver($app, $app['logger']);
});

// Services
$app['henkService'] = $app->share(function($app) {
	return new \Tweakers\IHenkIt\Service\HenkService($app['orm.em']);
});

// Controllers
$app['build.controller'] = $app->share(function() use ($app) {
	return new \Tweakers\IHenkIt\Controller\BuildController($app['henkService']);
});
$app['henk.controller'] = $app->share(function() use ($app) {
	return new \Tweakers\IHenkIt\Controller\HenkController($app['henkService']);
});

// Templating
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => array(__DIR__.'/../src/Tweakers/IHenkIt/View'),
));

// Routing
$app->get('/list', 'build.controller:buttonAction');
$app->post('/henk', 'henk.controller:registerAction');
$app->get('/', 'build.controller:indexAction');

$app['http_cache']->run();
