<?php

namespace App\Repository\Post;

use App\Model\Post;

interface PostRepository
{
    public function getAll(int $page = null, int $limit = null): array;
    public function getTotal(): int;
    public function get(int $id): ?Post;
    public function getBySlug(string $slug): ?Post;
    public function save(Post $post): ?Post;
}
