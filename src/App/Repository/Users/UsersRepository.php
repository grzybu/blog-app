<?php

namespace App\Repository\Users;

use App\Model\User;

interface UsersRepository
{
    public function getUserByUsername(string $login): ?User;
    public function addUser(string $userName, string $passwordHash, string $displayName): bool;
}
