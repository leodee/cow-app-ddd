<?php

namespace App\Presentation\Controller;

use App\Domain\Event;
use App\Domain\EventRepositoryInterface;
use App\Infrastructure\Http\SessionAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

abstract class AbstractController
{
    public function __construct(
        protected readonly UrlGenerator $urlGenerator,
        protected readonly Environment $twig,
        protected readonly SessionAuth $sessionAuth,
        protected readonly EventRepositoryInterface $eventRepository,
    ) {
    }

    protected function render(string $template, array $parameters = []): Response
    {
        return new Response(
            $this->twig->render($template, $parameters)
        );
    }

    protected function redirect(string $routeName, array $parameters = []): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate($routeName, $parameters)
        );
    }

    protected function addFlash(string $type, string $message): void
    {
        $this->sessionAuth->addFlash($type, $message);
    }

    protected function getFlash(string $type): ?string
    {
        return $this->sessionAuth->getFlash($type);
    }

    protected function track(string $action): void
    {
        $userId = $this->sessionAuth->getUserId();
        if ($userId) {
            $event = Event::record($userId, $action);
            $this->eventRepository->save($event);
        }
    }
}
