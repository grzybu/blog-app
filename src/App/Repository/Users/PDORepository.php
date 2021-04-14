<?php

namespace App\Repository\Users;

use App\Model\User;

class PDORepository implements UsersRepository
{
    protected \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByUsername(string $username): ?User
    {
        $query = $this->connection->prepare('SELECT * FROM `users` WHERE `username`=:username');
        $query->bindParam("username", $username, \PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return (new User())->fromArray($result);
    }

    public function addUser(string $userName, string $passwordHash, string $displaName): bool
    {
        $query = $this->connection->prepare('INSERT INTO `users` (`username`, `password`, `display_name` ) VALUES (:username, :password, :display_name)');
        $query->bindValue('username', $userName);
        $query->bindValue('password', $passwordHash);
        $query->bindValue('display_name', $displaName);
        $query->execute();
        return $this->connection->lastInsertId() > 0;
    }
}
