<?php

namespace App\Repository\Posts;

use App\Model\Post;

interface PostRepository
{
    public function getAll(int $limit = null, int $page = null): array;
    public function getTotal(): int;
    public function get(int $id): ?Post;
    public function getBySlug(string $slug): ?Post;
    public function save(Post $post): ?Post;
}
