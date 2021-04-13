<?php

namespace App\Repository\Posts;

use App\Model\Post;
use PHPUnit\Framework\TestCase;

class InMemoryRepositoryTest extends TestCase
{

    protected array $posts;

    public function setUp(): void
    {
        $this->posts =  [
            1 => new \App\Model\Post('Title 1', 'Summary of post 1', 'Post 1 content'),
            2 => new \App\Model\Post('Title 2', 'Summary of post 2', 'Post 2 content'),
        ];
    }

    public function testGetPosts(): void
    {
        $repository = new InMemoryRepository($this->posts);
        $this->assertIsArray($repository->getAll());
        $this->assertEquals(count($this->posts), count($repository->getAll()));
        $this->assertInstanceOf(Post::class, current($repository->getAll()));
    }

    public function testGetPost(): void
    {
        $post = new \App\Model\Post('Title 1', 'Summary of post 1', 'Post 1 content');
        $repository = new InMemoryRepository([ 1 => $post ]);

        $this->assertEquals($post, $repository->getPost(1));
        $this->assertNull($repository->getPost(2));
    }
}
