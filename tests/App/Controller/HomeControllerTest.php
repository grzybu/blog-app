<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use App\Repository\Posts\PostRepository;
use App\Tests\Traits\GetEnvironmentTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeControllerTest extends TestCase
{
    use GetEnvironmentTrait;

    private MockObject $repository;
    private Environment $environment;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PostRepository::class)->disableOriginalConstructor()->getMock();
        $this->environment = $this->getEnvironment();
    }

    public function testController(): void
    {
        $controller = new HomeController($this->repository, $this->environment);
        $request = new Request();
        $response = $controller($request);
        $this->assertInstanceOf(Response::class, $response);
    }
}
