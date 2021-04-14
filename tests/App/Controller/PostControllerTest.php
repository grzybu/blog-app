<?php

namespace App\Controller;

use App\Model\Post;
use App\Repository\Posts\PostRepository;
use App\Tests\Traits\GetEnvironmentTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PostControllerTest extends TestCase
{
    use GetEnvironmentTrait;

    private MockObject $repository;
    private Environment $environment;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PostRepository::class)->disableOriginalConstructor()->getMock();
        $this->environment = $this->getEnvironment();
    }

    public function testControllerPostNotFound(): void
    {
        $controller = new PostController($this->repository, $this->environment);
        $request = new Request(['slug' => 'slug']);

        $this->repository->expects($this->once())
            ->method('getBySlug')
            ->with('slug')
            ->willReturn(null);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testController(): void
    {
        $controller = new PostController($this->repository, $this->environment);

        $postSlug = 'post-title';
        $postTitle = 'post title';
        $request = new Request(['slug' => $postSlug]);

        $post = (new Post())->fromArray(
            [
                'id'         => 1,
                'user_id'    => 1,
                'title'      => $postTitle,
                'slug'       => 'post-slug',
                'body'       => 'body',
                'summary'    => 'summary',
                'created_at' => '2021-01-01 20:00:00',
                'updated_at' => '2021-01-01 21:00:00',
            ]
        );

        $this->repository->expects($this->once())
            ->method('getBySlug')
            ->willReturn($post);

        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString("<h3>${postTitle}</h3>", $response->getContent());
    }
}
