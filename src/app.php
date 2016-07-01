<?php
/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 29.06.2016
 * Time: 21:31
 */

$app->register(new \Silex\Provider\DoctrineServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(),array(
    'twig.path' => '../app/views',));

$app['repository.user'] = $app->share(function ($app) {
    return new TestTask\Repository\UserRepository($app['db']);
});