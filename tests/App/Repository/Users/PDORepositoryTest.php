<?php

namespace App\Tests\Repository\Users;

use App\Model\User;
use App\Repository\Users\PDORepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PDORepositoryTest extends TestCase
{

    /** @var MockObject|\PDO */
    protected MockObject $pdo;
    /** @var \PDOStatement|MockObject */
    protected $stmt;


    public function setUp(): void
    {
        $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
        $this->stmt = $this->getMockBuilder(\PDOStatement::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @incomplete
     */
    public function testGetUser(): void
    {
        $repository = new PDORepository($this->pdo);

        $data = [
            'id'       => 1,
            'username' => 'login',
            'password' => 'password',
        ];


        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->any())
            ->method('fetch')
            ->withConsecutive()
            ->willReturnOnConsecutiveCalls($data, null);

        $user = (new User())->fromArray($data);

        $this->assertEquals($user, $repository->getUserByUsername('login'));
        $this->assertNull($repository->getUserByUsername('login2'));
    }



    public function testSaveUser(): void
    {
        $login = 'login';
        $password = 'password';

        $repository = new PDORepository($this->pdo);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->assertIsBool($repository->addUser($login, $password));
    }
}
