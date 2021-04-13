<?php

use App\Lib\DbConnection;
use function DI\create;

return [
    'DB'                                         => DI\autowire(\PDO::class)->constructor($_ENV['DATABASE_DNS'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD']),
    \App\Repository\Posts\PostRepository::class  => DI\autowire(\App\Repository\Posts\PDORepository::class)->constructor(DI\get('DB')),
    \App\Repository\Users\UsersRepository::class => DI\autowire(\App\Repository\Users\PDORepository::class)->constructor(DI\get('DB')),

    \Symfony\Component\HttpFoundation\Request::class => function () {
        return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    },
    'SessionManager' => DI\autowire(\App\Libs\SessionManager::class)->constructor('BLOGAPPSESSION'),
    \App\Service\AuthService::class => DI\autowire(\App\Service\AuthService::class)->constructor(DI\get(\App\Repository\Users\UsersRepository::class)),

    \Symfony\Component\String\Slugger\SluggerInterface::class => DI\autowire(\Symfony\Component\String\Slugger\AsciiSlugger::class),
    \Twig\Environment::class                         => function ($container) {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/src/templates');
        $environment =  new Twig\Environment($loader);

        /** @var \App\Service\AuthService $authService */
        $authService = $container->get(\App\Service\AuthService::class);

        $environment->addGlobal('userAuthenticated', $authService->isAuthenticated());
        $environment->addGlobal('userIdentity', $authService->getIdentity());

        return $environment;
    },


];
