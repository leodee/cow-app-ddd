<?php

namespace App\Infrastructure\Bootstrap;

use App\Application\Service\AuthService;
use App\Infrastructure\Http\NativeSessionStorage;
use App\Infrastructure\Http\SessionAuth;
use App\Infrastructure\Persistence\DoctrineConnectionFactory;
use App\Infrastructure\Persistence\DoctrineUserRepository;
use App\Infrastructure\Persistence\DoctrineEventRepository;
use Twig\Environment;
use Doctrine\DBAL\Connection;

class Container
{
    public readonly Environment $twig;
    public readonly SessionAuth $sessionAuth;
    public readonly Connection $connection;
    public readonly DoctrineUserRepository $userRepository;
    public readonly DoctrineEventRepository $eventRepository;
    public readonly AuthService $authService;

    public function __construct()
    {
        $this->sessionAuth = new SessionAuth(new NativeSessionStorage());

        $this->connection = DoctrineConnectionFactory::create();
        $this->userRepository = new DoctrineUserRepository($this->connection);
        $this->eventRepository = new DoctrineEventRepository($this->connection);
        $this->authService = new AuthService($this->userRepository, $this->sessionAuth);
    }
}
