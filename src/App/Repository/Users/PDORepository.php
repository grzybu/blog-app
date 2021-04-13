<?php

namespace App\Repository\Post;

use App\Lib\DbConnection;
use App\Lib\PDOConnection;
use App\Model\Post;

class DbRepository implements PostRepository
{
    protected \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(int $page = 1, int $limit = 2): array
    {
        $offset = ($page > 0 ? $page - 1: 0) * $limit;

        $query = 'SELECT * FROM `posts` ORDER BY `created_at` DESC ';
        $query .= " LIMIT $offset, $limit";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $posts = $stmt->fetchAll();

        return $this->buildArray($posts);
    }

    public function get(int $id): ?Post
    {
        // TODO: Implement get() method.
    }

    public function getBySlug(string $slug): ?Post
    {
        $query = 'SELECT * FROM `posts` WHERE `slug` = ?';
        $stmt = $this->connection->prepare($query);

        $stmt->execute([$slug]);
        $post = $stmt->fetch();

        if (!$post) {
            return null;
        }

        return new Post($post['slug'], $post['title'], $post['summary'], $post['body']);
    }

    public function save(Post $post): ?Post
    {
        // TODO: Implement save() method.
    }

    private function buildArray(array $statementResults): array
    {
        return array_map(
            fn($result) => new Post(
                $result['slug'],
                $result['title'],
                $result['summary'],
                $result['body']
            ),
            $statementResults
        );
    }

    public function getTotal(): int
    {
        $query = 'SELECT count(*) as `total` FROM `posts`';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn() ?: 0;
    }
}
