<?php
/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 29.06.2016
 * Time: 21:31
 */

require_once ('../vendor/autoload.php');

$app = new \Silex\Application();

$app['debug'] = true;

include_once ('../src/app.php');
include_once ('../app/config/prod.php');
include_once ('../src/routes.php');

$app->run();