<?php

namespace App\Repository\Posts;

use App\Model\Post;
use App\Model\User;
use App\Utils\Clock;
use PDO;
use Symfony\Component\String\Slugger\SluggerInterface;

class PDORepository implements PostRepository
{
    protected PDO $connection;
    protected SluggerInterface $slugger;

    public function __construct(PDO $connection, SluggerInterface $slugger)
    {
        $this->connection = $connection;
        $this->slugger = $slugger;
    }

    public function getAll(int $limit = null, int $page = null): array
    {
        $offset = ($page > 0 ? $page - 1 : 0) * $limit;

        $query = 'SELECT * FROM `posts` ORDER BY `created_at` DESC ';

        if ($page && $limit) {
            $query .= " LIMIT $offset, $limit";
        }

        if (!$page && $limit > 0) {
            $query .= " LIMIT $limit";
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $posts = $stmt->fetchAll();

        return $this->buildArray($posts);
    }

    public function get(int $id): ?Post
    {
        $query = $this->connection->prepare('SELECT * FROM `posts` WHERE `id`=:id');
        $query->bindParam("id", $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        return (new Post())->fromArray($result);
    }

    public function getBySlug(string $slug): ?Post
    {
        $sql = 'SELECT `p`.*, `u`.`id` AS `userId`, `u`.`display_name` AS `userDisplayName`  FROM `posts` AS `p` '
            . ' LEFT JOIN `users` as `u` ON `p`.`user_id` = `u`.`id` '
            . ' WHERE `slug` =:slug';
        $query = $this->connection->prepare($sql);
        $query->bindParam('slug', $slug, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $post = (new Post())->fromArray($result);
        if ($post->getUserId()) {
            $user = (new User())->setDisplayName($result['userDisplayName'])->setId($result['userId']);
            $post->setUser($user);
        }

        return $post;
    }

    public function save(Post $post): ?Post
    {
        $slug = $this->slugger->slug($post->getTitle());
        $post->setSlug(strtolower($slug));

        if (!$post->getId()) {
            $post->setCreatedAt(Clock::now());
        }

        $post->setUpdatedAt(Clock::now());

        if (!$post->getId()) {
            return $this->insert($post);
        }
        return $this->update($post);
    }

    private function insert(Post $post): Post
    {
        $data = $post->toArray();
        unset($data['id']);

        $insertSql = 'INSERT INTO `posts` ';
        $columns = array_keys($data);
        $insertSql .= " (`" . implode("`, `", $columns) . "`)";
        $insertSql .= " VALUES (:" . implode(", :", $columns) . ") ";

        $query = $this->connection->prepare($insertSql);
        foreach ($data as $key => $value) {
            $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $query->execute();

        return $post->setId((int) $this->connection->lastInsertId());
    }

    private function update(Post $post): Post
    {
        $data = $post->toArray();

        $id = $post->getId();

        unset($data['id'], $data['created_at']);

        $updateSql = 'UPDATE `posts` SET ';
        foreach (array_keys($data) as $key) {
            $updateSql .= sprintf(" `%s` =:%s,", $key, $key);
        }

        $updateSql = rtrim($updateSql, ',');

        $updateSql .= ' WHERE id =:id';

        $query = $this->connection->prepare($updateSql);

        foreach ($data as $key => $value) {
            $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();

        return $post;
    }

    private function buildArray(array $statementResults): array
    {
        return array_map(
            fn($result) => (new Post())->fromArray($result),
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
