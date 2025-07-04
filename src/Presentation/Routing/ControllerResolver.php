<?php

namespace App\Presentation\Routing;

class ControllerResolver
{
    public function __construct(private array $controllerMap) {}

    public function resolve(string $controllerKey): callable
    {
        $callable = $this->controllerMap[$controllerKey] ?? null;

        if (!is_callable($callable)) {
            throw new \RuntimeException(sprintf('Controller "%s" not found or not callable.', $controllerKey));
        }

        return $callable;
    }
}