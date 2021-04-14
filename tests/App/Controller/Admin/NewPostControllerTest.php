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

class NewPostControllerTest extends TestCase
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
        $controller = new NewPostController($this->repository, $this->environment, $this->authService);
        $request = new Request();

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testShowNewPostForm(): void
    {
        $controller = new NewPostController($this->repository, $this->environment, $this->authService);
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $request = new Request();

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('<form method="post">', $response->getContent());
    }

    public function testAddNewPost(): void
    {
        $controller = new NewPostController($this->repository, $this->environment, $this->authService);
        $userId = 1;
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);
        $this->authService->expects($this->once())
            ->method('getUserId')
            ->willReturn($userId);

        $id = 1;
        $data = [
            'title' => 'title',
            'body' => 'body',
            'summary' => 'summary',
        ];

        $post = (new Post())->fromArray(array_merge($data, ['user_id' => $userId, 'id' => $id]));

        $this->repository->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willReturn($post);

        $request = new Request();
        $request->setMethod('POST');
        $request->request->add($data);

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/admin/post/' . $id, $response->getContent());
    }

    public function testAddNewPostFailed(): void
    {
        $controller = new NewPostController($this->repository, $this->environment, $this->authService);
        $userId = 1;
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);
        $this->authService->expects($this->once())
            ->method('getUserId')
            ->willReturn($userId);

        $data = [
            'title' => 'title',
            'body' => 'body',
            'summary' => 'summary',
        ];

        $this->repository->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willThrowException(new \Exception('DB Error'));

        $request = new Request();
        $request->setMethod('POST');
        $request->request->add($data);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('DB Error', $response->getContent());
    }
}
