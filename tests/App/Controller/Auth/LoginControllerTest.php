<?php

namespace App\Controller\Auth;

use App\Service\AuthService;
use App\Tests\Traits\GetEnvironmentTrait;
use http\Env\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class LoginControllerTest extends TestCase
{
    use GetEnvironmentTrait;

    private MockObject $authService;
    private Environment $environment;

    public function setUp(): void
    {
        $this->authService = $this->getMockBuilder(AuthService::class)->disableOriginalConstructor()->getMock();
        $this->environment = $this->getEnvironment();
    }

    public function testIsAuthenticated(): void
    {
        $controller = new LoginController($this->authService, $this->environment);
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $request = new Request();

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testIsAuthenticationSuccess(): void
    {
        $controller = new LoginController($this->authService, $this->environment);
        $userName = 'user';
        $password = 'password';

        $this->authService->expects($this->once())
            ->method('authenticate')
            ->with($userName, $password)
            ->willReturn(true);

        $newUrl = '/new-url';
        $request = new Request(['returnTo' => base64_encode($newUrl)]);
        $request->setMethod('post');
        $request->request->add(['username' => $userName, 'password' => $password]);

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString($newUrl, $response->getContent());
    }

    public function testIsAuthenticationFailed(): void
    {
        $controller = new LoginController($this->authService, $this->environment);
        $userName = 'user';
        $password = 'password';

        $this->authService->expects($this->once())
            ->method('authenticate')
            ->with($userName, $password)
            ->willReturn(false);

        $newUrl = '/new-url';
        $request = new Request(['returnTo' => base64_encode($newUrl)]);
        $request->setMethod('post');
        $request->request->add(['username' => $userName, 'password' => $password]);

        $response = $controller($request);
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
        $this->assertStringContainsString("Login failed", $response->getContent());
    }

    public function testGetRequest(): void
    {
        $controller = new LoginController($this->authService, $this->environment);
        $newUrl = '/new-url';
        $request = new Request(['returnTo' => base64_encode($newUrl)]);
        $response = $controller($request);
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
        $this->assertStringContainsString('<form method="post">', $response->getContent());
    }
}
