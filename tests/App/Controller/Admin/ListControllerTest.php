<?php

namespace App\Controller\Admin;

use App\Model\Post;
use App\Repository\Posts\PostRepository;
use App\Service\AuthService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListControllerTest extends TestCase
{
    private MockObject $repository;
    /**
     * @var MockObject|Environment
     */
    private MockObject $environment;

    private MockObject $authService;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PostRepository::class)->disableOriginalConstructor()->getMock();
        $this->environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $this->authService = $this->getMockBuilder(AuthService::class)->disableOriginalConstructor()->getMock();
    }

    public function testNotAuthenticated(): void
    {
        $controller = new ListController($this->repository, $this->environment, $this->authService);
        $request = new Request();

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testController(): void
    {
        $controller = new ListController($this->repository, $this->environment, $this->authService);
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $request = new Request();

        $title = 'post-title';

        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn([(new Post())->setTitle($title)]);

        $this->environment->expects($this->once())
            ->method('render')
            ->withAnyParameters()
            ->willReturn("<ul><li>${title}</li></ul>");

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString("<ul><li>${title}</li></ul>", $response->getContent());
    }
}
