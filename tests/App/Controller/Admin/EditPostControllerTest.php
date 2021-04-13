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

class EditPostControllerTest extends TestCase
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
        $controller = new EditPostController($this->repository, $this->environment, $this->authService);
        $request = new Request();

        $response = $controller($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testShowEditPostForm(): void
    {
        $controller = new EditPostController($this->repository, $this->environment, $this->authService);
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $request = new Request(['id' => 1]);

        $this->environment->expects($this->once())
            ->method('render')
            ->withAnyParameters()
            ->willReturn("<h3>Edit post form</h3><form></form>");

        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(new Post());

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString("<form>", $response->getContent());
    }

    public function testPostNotFound(): void
    {
        $controller = new EditPostController($this->repository, $this->environment, $this->authService);
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $request = new Request(['id' => 1]);

        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertStringContainsString("Post not found", $response->getContent());
    }

    public function testEditPost(): void
    {
        $controller = new EditPostController($this->repository, $this->environment, $this->authService);
        $userId = 1;
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $id = 1;
        $data = [
            'title'   => 'title',
            'body'    => 'body',
            'summary' => 'summary',
        ];

        $post = (new Post())->fromArray(array_merge($data, ['user_id' => $userId]));

        $this->repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($post);


        $this->repository->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willReturn($post);

        $request = new Request(['id' => $id]);
        $request->setMethod('POST');
        $request->request->add($data);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testEditPostFailed(): void
    {
        $this->authService->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $data = [
            'title'   => 'title',
            'body'    => 'body',
            'summary' => 'summary',
        ];

        $this->repository->expects($this->once())
            ->method('get')
            ->with(1)
            ->willReturn(new Post());

        $request = new Request(['id' => 1]);
        $request->setMethod('POST');
        $request->request->add($data);

        $this->environment->expects($this->once())
            ->method('render')
            ->withAnyParameters()
            ->willReturn("<p>DB Error</p><form></form>");
        $this->repository->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willThrowException(new \Exception('DB Error'));

        $controller = new EditPostController($this->repository, $this->environment, $this->authService);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('DB Error', $response->getContent());
    }
}
