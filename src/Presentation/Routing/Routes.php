<?php

namespace App\Presentation\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Routes
{
    public static function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $routes->add('home', new Route('/', ['_controller' => 'page_home']));
        $routes->add('page_a', new Route('/page-a', ['_controller' => 'page_a']));
        $routes->add('page_b', new Route('/page-b', ['_controller' => 'page_b']));
        $routes->add('buy_cow', new Route('/buy-cow', ['_controller' => 'buy_cow']));
        $routes->add('download', new Route('/page-b/download', ['_controller' => 'download']));

        $routes->add('register', new Route('/register', ['_controller' => 'auth_register']));
        $routes->add('login', new Route('/login', ['_controller' => 'auth_login']));
        $routes->add('logout', new Route('/logout', ['_controller' => 'auth_logout']));

        $routes->add('statistics', new Route('/admin/statistics', ['_controller' => 'admin_statistics']));
        $routes->add('report', new Route('/admin/report', ['_controller' => 'admin_report']));

        return $routes;
    }
}
