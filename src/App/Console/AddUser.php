<?php

namespace App\Console;

use App\Repository\Users\UsersRepository;

class AddUser
{
    protected UsersRepository $repository;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $username, string $password): void
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        if ($this->repository->getUserByUsername($username)) {
            throw new \InvalidArgumentException("User ${username} already exists");
        }
        $this->repository->addUser($username, $passwordHash);

        print 'User added';
    }
}
