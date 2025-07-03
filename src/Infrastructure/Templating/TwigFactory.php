<?php

namespace App\Infrastructure\Templating;

use App\Infrastructure\Http\SessionAuth;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigFactory
{
    public static function create(
        string $templatePath,
        SessionAuth $sessionAuth,
        UrlGenerator $urlGenerator
    ): Environment {
        $loader = new FilesystemLoader($templatePath);
        $twig = new Environment($loader);

        $twig->addGlobal('url_generator', $urlGenerator);
        $twig->addGlobal('username', $sessionAuth->getUsername());
        $twig->addGlobal('is_admin', $sessionAuth->isAdmin());

        return $twig;
    }}
