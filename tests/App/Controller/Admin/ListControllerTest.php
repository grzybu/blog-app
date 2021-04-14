<?php

namespace App\Controller\Admin;

use App\Model\Post;
use App\Repository\Posts\PostRepository;
use App\Service\AuthService;
use App\Tests\Traits\GetEnvironmentTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListControllerTest extends TestCase
{
    use GetEnvironmentTrait;

    private MockObject $repository;
    private Environment $environment;
    private MockObject $authService;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PostRepository::class)->disableOriginalConstructor()->getMock();
        $this->environment = $this->getEnvironment();
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
            ->willReturn([(new Post())->setTitle($title)->setId(1)]);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('<li class="blog-post">post-title - <a href="/admin/post/1">edit</a></li>', $response->getContent());
    }
}
