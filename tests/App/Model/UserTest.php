<?php

namespace App\Tests\Model;

use App\Model\Post;
use App\Utils\Clock;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function setUp(): void
    {
        Clock::freeze();
    }

    public function tearDown(): void
    {
        Clock::release();
    }

    public function testFromArrayAndToArray(): void
    {
        $now = Clock::now();

        $data = [
            'id'         => 1,
            'title'      => 'post title',
            'body'       => 'body',
            'summary'    => 'summary',
            'slug'       => 'post-title',
            'user_id'    => 1,
            'created_at' => $now->format('Y-m-d H:i:s'),
            'updated_at'  => $now->format('Y-m-d H:i:s'),
        ];

        $model = new Post();
        $model->fromArray($data);

        $this->assertEquals(1, $model->getId());
        $this->assertEquals('post title', $model->getTitle());
        $this->assertEquals('body', $model->getBody());
        $this->assertEquals('summary', $model->getSummary());
        $this->assertEquals(1, $model->getUserId());
        $this->assertEquals('post-title', $model->getSlug());
        $this->assertEquals($now, $model->getCreatedAt());
        $this->assertEquals($now, $model->getUpdatedAt());

        $this->assertEquals($data, $model->toArray());
    }
}
