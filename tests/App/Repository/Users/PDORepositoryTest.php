<?php

namespace App\Tests\Repository\Posts;

use App\Model\Post;
use App\Repository\Posts\PDORepository;
use App\Utils\Clock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class PDORepositoryTest extends TestCase
{

    /** @var MockObject|\PDO */
    protected MockObject $pdo;
    /** @var MockObject|SluggerInterface */
    protected MockObject $slugger;
    /** @var \PDOStatement|MockObject */
    protected $stmt;


    public function setUp(): void
    {
        $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
        $this->slugger = $this->getMockBuilder(SluggerInterface::class)->disableOriginalConstructor()->getMock();
        $this->stmt = $this->getMockBuilder(\PDOStatement::class)->disableOriginalConstructor()->getMock();
        Clock::freeze();
    }

    public function tearDown(): void
    {
        Clock::release();
    }

    public function testGetPosts(): void
    {
        $repository = new PDORepository($this->pdo, $this->slugger);
        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->any())
            ->method('fetchAll')
            ->willReturn(
                [
                    [
                        'id'         => 1,
                        'title'      => 'post title',
                        'body'       => 'body',
                        'summary'    => 'summary',
                        'user_id'    => 1,
                        'created_at' => Clock::now()->format('Y-m-d H:i:s'),
                        'update_at'  => Clock::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        $this->assertIsArray($repository->getAll());
        $this->assertEquals(1, count($repository->getAll()));
        $this->assertInstanceOf(Post::class, current($repository->getAll()));
    }

    /**
     * @incomplete
     */
    public function testGetPost(): void
    {
        $repository = new PDORepository($this->pdo, $this->slugger);

        $data = [
            'id'         => 1,
            'title'      => 'post title',
            'body'       => 'body',
            'summary'    => 'summary',
            'user_id'    => 1,
            'created_at' => Clock::now()->format('Y-m-d H:i:s'),
            'update_at'  => Clock::now()->format('Y-m-d H:i:s'),
        ];


        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->any())
            ->method('fetch')
            ->withConsecutive()
            ->willReturnOnConsecutiveCalls($data, null);

        $post = (new Post())->fromArray($data);

        $this->assertEquals($post, $repository->get(1));
        $this->assertNull($repository->get(2));
    }

    public function testGetBySlug(): void
    {
        $slug = 'post-slug';
        $repository = new PDORepository($this->pdo, $this->slugger);
        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $data = [
            'id'         => 1,
            'title'      => 'post title',
            'body'       => 'body',
            'summary'    => 'summary',
            'slug'       => $slug,
            'user_id'    => 1,
            'created_at' => Clock::now()->format('Y-m-d H:i:s'),
            'update_at'  => Clock::now()->format('Y-m-d H:i:s'),
        ];

        $this->stmt->expects($this->any())
            ->method('fetch')
            ->withConsecutive()
            ->willReturnOnConsecutiveCalls($data, null);


        $result = $repository->getBySlug($slug);
        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals($slug, $result->getSlug());
        $this->assertNull($repository->getBySlug('slug-2'));
    }

    public function testSaveExistingPost(): void
    {
        $newPostTitle = 'post title new';

        $data = [
            'id'         => 1,
            'title'      => $newPostTitle,
            'body'       => 'body',
            'summary'    => 'summary',
            'slug'       => 'post-slug',
            'user_id'    => 1,
            'created_at' => '2020-01-01 00:00:01',
            'update_at'  => '2020-01-02 00:00:02',
        ];
        $post = (new Post())->fromArray($data);

        $repository = new PDORepository($this->pdo, $this->slugger);

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with($newPostTitle)
            ->willReturn(new UnicodeString('post-title-new'));

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);


        $return = $repository->save($post);
        $this->assertInstanceOf(Post::class, $return);
        $this->assertEquals('post-title-new', $return->getSlug());
        $this->assertEquals(Clock::now(), $return->getUpdatedAt());
    }

    public function testSaveNewPost(): void
    {
        $postTitle = 'post title new';

        $data = [
            'title'   => $postTitle,
            'body'    => 'body',
            'summary' => 'summary',
            'user_id' => 1,
        ];

        $post = (new Post())->fromArray($data);

        $repository = new PDORepository($this->pdo, $this->slugger);

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with($postTitle)
            ->willReturn(new UnicodeString('post-title-new'));

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);


        $return = $repository->save($post);
        $this->assertInstanceOf(Post::class, $return);
        $this->assertEquals('post-title-new', $return->getSlug());
        $this->assertEquals(Clock::now(), $return->getUpdatedAt());
        $this->assertEquals(Clock::now(), $return->getCreatedAt());
        $this->assertEquals(1, $return->getId());
    }
}
