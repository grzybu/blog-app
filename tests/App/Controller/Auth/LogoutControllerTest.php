<?php

namespace App\Controller\Auth;

use App\Service\AuthService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LogoutControllerTest extends TestCase
{
    private MockObject $authService;

    public function setUp(): void
    {
        $this->authService = $this->getMockBuilder(AuthService::class)->disableOriginalConstructor()->getMock();
    }

    public function testLogout(): void
    {
        $controller = new LogoutController($this->authService);
        $request = new Request();
        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
