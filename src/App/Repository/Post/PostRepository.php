<?php

namespace App\Repository\Post;

use App\Model\Post\Post;

interface PostRepository
{
    /** Post[] */
    public function getPosts(): array;
    public function getArticle(int $id): Post;

}