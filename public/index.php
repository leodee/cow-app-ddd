<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use App\Infrastructure\Bootstrap\Container;
use App\Presentation\Controller\PageController;
use App\Presentation\Controller\AuthController;
use App\Presentation\Controller\AdminController;
use App\Presentation\Routing\Routes;
use App\Infrastructure\Templating\TwigFactory;

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$container = new Container();

$emptyRoutes = new RouteCollection();
$urlGenerator = new UrlGenerator($emptyRoutes, $context);
$twig = TwigFactory::create(__DIR__ . '/../src/Presentation/Templates', $container->sessionAuth, $urlGenerator);

$authController = new AuthController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository, $container->authService);
$pageController = new PageController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);
$adminController = new AdminController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);

$routes = Routes::getRoutes($pageController, $authController, $adminController);
$urlGenerator = new UrlGenerator($routes, $context);
$twig = TwigFactory::create(__DIR__ . '/../src/Presentation/Templates', $container->sessionAuth, $urlGenerator);

$authController = new AuthController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository, $container->authService);
$pageController = new PageController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);
$adminController = new AdminController($urlGenerator, $twig, $container->sessionAuth, $container->eventRepository);

$routes = Routes::getRoutes($pageController, $authController, $adminController);

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    $controllerCallable = $parameters['_controller'];
    $routeName = $parameters['_route'] ?? '';
    unset($parameters['_controller'], $parameters['_route']);

    $publicRoutes = ['register', 'login'];
    $adminRoutes = ['statistics', 'report'];

    if (!in_array($routeName, $publicRoutes, true) && !$container->sessionAuth->getUserId()) {
        header('Location: /login');
        exit;
    }

    if (in_array($routeName, $adminRoutes, true) && !$container->sessionAuth->isAdmin()) {
        $response = new Response('Access denied', 403);
    } else {
        $response = call_user_func_array($controllerCallable, [$request, $parameters]);
    }
} catch (ResourceNotFoundException $e) {
    $response = new Response('Page not found', 404);
} catch (Throwable $e) {
    $response = new Response('Internal server error: ' . $e->getMessage(), 500);
}

$response->send();
