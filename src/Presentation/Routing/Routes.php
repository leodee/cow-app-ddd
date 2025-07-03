<?php

namespace App\Presentation\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Presentation\Controller\PageController;
use App\Presentation\Controller\AuthController;
use App\Presentation\Controller\AdminController;

class Routes
{
    public static function getRoutes(
        PageController $pageController,
        AuthController $authController,
        AdminController $adminController
    ): RouteCollection {
        $routes = new RouteCollection();

        $routes->add('home', new Route('/', ['_controller' => [$pageController, 'home']]));
        $routes->add('page_a', new Route('/page-a', ['_controller' => [$pageController, 'pageA']]));
        $routes->add('page_b', new Route('/page-b', ['_controller' => [$pageController, 'pageB']]));
        $routes->add('buy_cow', new Route('/buy-cow', ['_controller' => [$pageController, 'buyCow']]));
        $routes->add('download', new Route('/page-b/download', ['_controller' => [$pageController, 'download']]));

        $routes->add('register', new Route('/register', ['_controller' => [$authController, 'register']]));
        $routes->add('login', new Route('/login', ['_controller' => [$authController, 'login']]));
        $routes->add('logout', new Route('/logout', ['_controller' => [$authController, 'logout']]));

        $routes->add('statistics', new Route('/admin/statistics', ['_controller' => [$adminController, 'statistics']]));
        $routes->add('report', new Route('/admin/report', ['_controller' => [$adminController, 'report']]));

        return $routes;
    }
}
