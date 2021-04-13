<?php

namespace App\Controller\Auth;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginController
{
    protected Environment $twig;
    protected AuthService $authService;
    protected ?string $returnTo = null;

    public function __construct(AuthService $authService, Environment $twig)
    {
        $this->twig = $twig;
        $this->authService = $authService;
    }

    public function __invoke(Request $request): Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse('/');
        }

        if ($request->get('returnTo')) {
            $this->returnTo = base64_decode($request->get('returnTo'));
        }

        $error = false;
        if ($request->getMethod() === Request::METHOD_POST) {
            $username = $request->get('username');
            $password = $request->get('password');
            $authenticated = $this->authService->authenticate($username, $password);

            if ($authenticated) {
                return new RedirectResponse($this->returnTo ?: '/');
            }

            $error = true;
        }


        $content = $this->twig->render('login.twig', ['error' => $error]);
        return new Response($content);
    }
}
