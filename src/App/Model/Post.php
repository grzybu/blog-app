<?php

namespace App\Model;

class Post implements Model
{
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    protected ?int $id = null;
    protected ?string $title = null;

    protected ?string $summary = null;
    protected ?string $body = null;
    protected ?\DateTimeImmutable $updatedAt = null;

    protected ?string $slug = null;
    protected ?\DateTimeImmutable $createdAt = null;

    protected ?int $userId = null;
    protected ?User $user = null;

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId = null): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null): self
    {
        $this->id = $id;
        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug = null): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'slug' => $this->getSlug(),
            'body' => $this->getBody(),
            'summary' => $this->getSummary(),
            'user_id' => $this->getUserId(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function fromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        if (isset($data['user_id'])) {
            $this->setUserId($data['user_id']);
        }

        if (isset($data['updated_at'])) {
            $this->setUpdatedAt((new \DateTimeImmutable())->setTimestamp(strtotime($data['updated_at'])));
        }
        if (isset($data['created_at'])) {
            $this->setCreatedAt((new \DateTimeImmutable())->setTimestamp(strtotime($data['created_at'])));
        }

        return $this;
    }
}
