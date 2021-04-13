<?php

namespace App\Service;

use App\Libs\SessionManager;
use App\Model\User;
use App\Repository\Users\UsersRepository;

class AuthService
{
    protected SessionManager $sessionManager;
    protected UsersRepository $userRepository;

    public function __construct(UsersRepository $repository, SessionManager $sessionManager)
    {
        $this->userRepository = $repository;
        $this->sessionManager = $sessionManager;
    }

    public function authenticate(string $username, string $password): bool
    {
        $user = $this->userRepository->getUserByUsername($username);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $this->sessionManager->put('user.id', $user->getId());
        $this->sessionManager->put('user.username', $user->getUsername());

        return true;
    }

    public function isAuthenticated(): bool
    {
        return $this->sessionManager->get('user.id') !== null;
    }

    public function getIdentity(): ?string
    {
        return $this->sessionManager->get('user.username');
    }

    public function getUserId(): ?int
    {
        return $this->sessionManager->get('user.id');
    }

    public function logout(): void
    {
        $this->sessionManager->forget();
    }
}
