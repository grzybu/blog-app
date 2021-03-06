<?php

namespace App\Model;

class User implements Model
{
    private int $id;
    private string $username;
    private string $password;
    private string $displayName;

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'display_name' => $this->getDisplayName(),
        ];
    }

    public function fromArray(array $data): self
    {
        return (new User())
            ->setId($data['id'] ?? null)
            ->setUserName($data['username'] ?? null)
            ->setPassword($data['password'] ?? null)
            ->setDisplayName($data['display_name'] ?? null);
    }
}
