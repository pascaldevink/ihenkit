<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../config/app-config.php';
$app['http_cache']->run();

return $app;
