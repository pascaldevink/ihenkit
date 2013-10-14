<?php

$app = new Silex\Application();

// Debugging
if ((isset($_REQUEST['userId']) && $_REQUEST['userId'] == 266225) || $_GET['trace'] = 1)
	$app['debug'] = true;

// Database configuration
include_once __DIR__.'/db-config.php';

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
$app['website.controller'] = $app->share(function() use ($app) {
	return new \Tweakers\IHenkIt\Controller\WebsiteController($app['henkService']);
});
$app['henk.controller'] = $app->share(function() use ($app) {
	return new \Tweakers\IHenkIt\Controller\HenkController($app['henkService']);
});
$app['metahenks.controller'] = $app->share(function() use ($app) {
    return new \Tweakers\IHenkIt\Controller\MetahenksController($app['henkService']);
});

// Templating
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => array(__DIR__.'/../src/Tweakers/IHenkIt/View'),
));

// Routing
$app->get('/metahenks', 'metahenks.controller:listAction');
$app->post('/henk', 'henk.controller:addHenkAction');
$app->get('/', 'website.controller:indexAction');

// Deprecated routing:
$app->get('/list', 'metahenks.controller:listAction');

return $app;