<?php

namespace App\Repository\Users;

use App\Model\User;

interface UserRepository
{
    public function getUserByUsername(string $login): ?User;

}