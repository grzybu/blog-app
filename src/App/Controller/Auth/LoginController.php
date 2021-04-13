<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginController
{
    protected Environment $twig;
    protected AuthService $authService;

    public function __construct(AuthService $authService, Environment $twig)
    {
        $this->twig = $twig;
        $this->authService = $authService;
    }

    public function __invoke(Request $request): Response
    {
        if ($this->authService->isAuthenticated()) {
            var_dump($this->authService->getIdentity());
            exit;
        }

        if ($request->getMethod() === Request::METHOD_POST) {
            $username = $request->get('username');
            $password = $request->get('password');
            $authenticated = $this->authService->authenticate($username, $password);

            if ($authenticated) {
                return new RedirectResponse('/');
            }
        }

        $content = $this->twig->render('login.twig');
        return new Response($content);
    }


}