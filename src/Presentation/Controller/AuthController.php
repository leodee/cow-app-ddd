<?php

namespace App\Presentation\Controller;

use App\Application\Service\AuthService;
use App\Domain\Event;
use App\Domain\EventRepositoryInterface;
use App\Infrastructure\Http\SessionAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Environment;

class AuthController extends AbstractController
{
    public function __construct(
        UrlGenerator $urlGenerator,
        Environment $twig,
        SessionAuth $sessionAuth,
        EventRepositoryInterface $eventRepository,
        private readonly AuthService $authService,
    ) {
        parent::__construct(
            urlGenerator: $urlGenerator,
            twig: $twig,
            sessionAuth: $sessionAuth,
            eventRepository: $eventRepository
        );
    }

    public function register(Request $request): Response
    {
        $flashMessage = '';

        if ($request->getMethod() === 'POST') {
            try {
                $this->authService->register(
                    $request->request->get('username'),
                    $request->request->get('password')
                );

                $this->track(Event::ACTION_REGISTER);

                return $this->redirect('login');
            } catch (\InvalidArgumentException $exception) {
                $flashMessage = $exception->getMessage();
            }
        }

        return $this->render('register.html.twig', [
            'flash_message' => $flashMessage
        ]);
    }

    public function login(Request $request): Response
    {
        $flashMessage = '';

        if ($request->getMethod() === 'POST') {
            if ($this->authService->login(
                $request->request->get('username'),
                $request->request->get('password')
            )) {
                $this->track(Event::ACTION_LOGIN);
                return $this->redirect('page_a');
            } else {
                $flashMessage = 'Invalid credentials';
            }
        }

        return $this->render('login.html.twig', [
            'flash_message' => $flashMessage
        ]);
    }

    public function logout(): Response
    {
        $userId = $this->sessionAuth->getUserId();
        $this->sessionAuth->logout();

        if ($userId) {
            $this->track(Event::ACTION_LOGOUT);
        }

        return $this->redirect('login');
    }
}
