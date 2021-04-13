<?php

namespace App\Tests\Console;

use App\Console\AddUser;
use App\Model\User;
use App\Repository\Users\UsersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddUserTest extends TestCase
{
    private MockObject $repository;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(UsersRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddUser()
    {
        $command = new AddUser($this->repository);
        $this->expectOutputString('User added', $command('user', 'passwrord'));
    }

    public function testUserExists()
    {
        $userName = 'user1';
        $password = 'pass';
        $command = new AddUser($this->repository);
        $this->repository->expects($this->once())
            ->method('getUserByUsername')
            ->willReturn((new User())->setUsername($userName)->setPassword($password)->setId(1));

        $this->expectExceptionMessage("User ${userName} already exists");
        $command($userName, $password);
    }
}
