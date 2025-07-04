<?php

use App\Infrastructure\Bootstrap\Container;
use App\Infrastructure\Templating\TwigFactory;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use App\Presentation\Routing\Routes;
use App\Presentation\Controller\AuthController;
use App\Presentation\Controller\PageController;
use App\Presentation\Controller\AdminController;
use App\Presentation\Routing\Dispatcher;
use App\Presentation\Routing\ControllerResolver;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$context = new RequestContext();

$routes = Routes::getRoutes();
$urlGenerator = new UrlGenerator($routes, $context);
$twig = TwigFactory::create(__DIR__ . '/../src/Presentation/Templates', $container->sessionAuth, $urlGenerator);

$authController = new AuthController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository, $container->authService);
$pageController = new PageController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);
$adminController = new AdminController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);

$controllerMap = [
    'page_home'       => [$pageController, 'home'],
    'page_a'          => [$pageController, 'pageA'],
    'page_b'          => [$pageController, 'pageB'],
    'buy_cow'         => [$pageController, 'buyCow'],
    'download'        => [$pageController, 'download'],

    'auth_register'   => [$authController, 'register'],
    'auth_login'      => [$authController, 'login'],
    'auth_logout'     => [$authController, 'logout'],

    'admin_statistics'=> [$adminController, 'statistics'],
    'admin_report'    => [$adminController, 'report'],
];

$resolver = new ControllerResolver($controllerMap);
$matcher = new UrlMatcher($routes, $context);
$dispatcher = new Dispatcher($matcher, $resolver, $container->sessionAuth);

$request = Request::createFromGlobals();
$response = $dispatcher->handle($request);
$response->send();
