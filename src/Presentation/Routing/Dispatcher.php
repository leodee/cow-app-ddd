<?php

namespace App\Presentation\Routing;

use App\Infrastructure\Http\SessionAuth;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class Dispatcher
{
    public function __construct(
        private readonly UrlMatcher $matcher,
        private readonly ControllerResolver $resolver,
        private readonly SessionAuth $sessionAuth
    ) {}

    public function handle(Request $request): Response
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $parameters = $this->matcher->match($request->getPathInfo());
            $controllerKey = $parameters['_controller'];
            $routeName = $parameters['_route'] ?? '';
            unset($parameters['_controller'], $parameters['_route']);

            $publicRoutes = ['register', 'login'];
            $adminRoutes = ['statistics', 'report'];

            if (!in_array($routeName, $publicRoutes, true) && !$this->sessionAuth->getUserId()) {
                return new Response('', 302, ['Location' => '/login']);
            }

            if (in_array($routeName, $adminRoutes, true) && !$this->sessionAuth->isAdmin()) {
                return new Response('Access denied', 403);
            }

            $controllerCallable = $this->resolver->resolve($controllerKey);
            $response = call_user_func_array($controllerCallable, [$request, $parameters]);

        } catch (ResourceNotFoundException $e) {
            $response = new Response('Page not found', 404);
        } catch (Throwable $e) {
            $response = new Response('Internal server error: ' . $e->getMessage(), 500);
        }

        return $response;
    }
}
