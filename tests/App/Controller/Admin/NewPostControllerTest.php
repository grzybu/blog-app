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

class NewPostControllerTest extends TestCase
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

        $this->environment->expects($this->once())
            ->method('render')
            ->withAnyParameters()
            ->willReturn("<h3>New post form</h3><form></form>");

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString("<form>", $response->getContent());
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
            'title'   => 'title',
            'body'    => 'body',
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
            'title'   => 'title',
            'body'    => 'body',
            'summary' => 'summary',
        ];

        $this->repository->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willThrowException(new \Exception('DB Error'));

        $request = new Request();
        $request->setMethod('POST');
        $request->request->add($data);

        $this->environment->expects($this->once())
            ->method('render')
            ->withAnyParameters()
            ->willReturn("<p>DB Error</p><form></form>");

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('DB Error', $response->getContent());
    }
}
