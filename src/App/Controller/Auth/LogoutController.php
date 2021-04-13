<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;

class LogoutController
{
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->authService->logout();
        return new RedirectResponse('/');
    }
}