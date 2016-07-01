<?php
/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 29.06.2016
 * Time: 21:32
 */

namespace TestTask\Controller;


use Silex\Application;

class IndexController
{

    public function showIndex(Application $app)
    {
        $users = $app['repository.user']->getAllUsers();
        foreach ($users as $user)
            $user->calcOutput();
        return $app['twig']->render('index.twig', array('users' => $users));
    }
}