<?php

namespace App\Service;

use App\Libs\SessionManager;
use App\Model\User;
use App\Repository\Users\UsersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    protected MockObject $sessionManager;
    protected MockObject $userRepository;

    public function setUp(): void
    {
        $this->sessionManager = $this->getMockBuilder(SessionManager::class)->disableOriginalConstructor()->getMock();
        $this->userRepository = $this->getMockBuilder(UsersRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testAuthenticate(): void
    {
        $password = 'passwd';
        $login = 'user1';
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $user = new User();
        $user->setPassword($passwordHash);
        $user->setId(1);
        $user->setUsername($login);

        $service = new AuthService($this->userRepository, $this->sessionManager);
        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($login)
            ->willReturn($user);


        $this->assertTrue($service->authenticate($login, $password));
    }

    public function testGetters(): void
    {
        $service = new AuthService($this->userRepository, $this->sessionManager);

        $userId = 1;
        $username = 'login';

        $this->sessionManager->method('get')
            ->withConsecutive(['user.id'], ['user.username'])
            ->willReturnOnConsecutiveCalls($userId, $username);

        $this->assertEquals($userId, $service->getUserId());
        $this->assertEquals($username, $service->getIdentity());
    }

    public function testCannotAuthenticateNoUser(): void
    {
        $service = new AuthService($this->userRepository, $this->sessionManager);
        $this->assertFalse($service->authenticate('username', 'password'));
    }

    public function testCannotAuthenticateWrongPassword(): void
    {
        $service = new AuthService($this->userRepository, $this->sessionManager);
        $password = 'passwd';
        $login = 'user1';
        $passwordHash = password_hash('wrongpassword', PASSWORD_BCRYPT, ['cost' => 12]);

        $user = new User();
        $user->setPassword($passwordHash);
        $user->setId(1);
        $user->setUsername($login);

        $service = new AuthService($this->userRepository, $this->sessionManager);
        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($login)
            ->willReturn($user);

        $this->assertFalse($service->authenticate($login, $password));
    }

    public function testIsAuthenticated(): void
    {
        $service = new AuthService($this->userRepository, $this->sessionManager);

        $userId = 1;

        $this->sessionManager->expects($this->once())->method('get')
            ->with('user.id')
            ->willReturn($userId);

        $this->assertTrue($service->isAuthenticated());
    }
    public function testLogout(): void
    {
        $service = new AuthService($this->userRepository, $this->sessionManager);
        $this->sessionManager->expects($this->once())->method('get')
            ->with('user.id')
            ->willReturn(null);
        $service->logout();
        $this->assertFalse($service->isAuthenticated());
    }
}
