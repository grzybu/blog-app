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
            'id'         => $this->getId(),
            'title'      => $this->getTitle(),
            'slug'       => $this->getSlug(),
            'body'       => $this->getBody(),
            'summary'    => $this->getSummary(),
            'user_id'    => $this->getUserId(),
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
        $model =  (new Post())
            ->setId($data['id'] ?? null)
            ->setBody($data['body'] ?? null)
            ->setSummary($data['summary'] ?? null)
            ->setUserId($data['user_id'] ?? null)
            ->setSlug($data['slug'] ?? null)
            ->setTitle($data['title'] ?? null);

        if (isset($data['updated_at'])) {
            $model->setUpdatedAt((new \DateTimeImmutable())->setTimestamp(strtotime($data['updated_at'])));
        }
        if (isset($data['created_at'])) {
            $model->setCreatedAt((new \DateTimeImmutable())->setTimestamp(strtotime($data['created_at'])));
        }

        return $model;
    }
}