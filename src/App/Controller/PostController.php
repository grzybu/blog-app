<?php

namespace App\Controller;

use App\Repository\Posts\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PostController
{
    protected PostRepository $repository;
    protected Environment $twig;
    public function __construct(PostRepository $repository, Environment $twig)
    {
        $this->repository = $repository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $post = $this->repository->getBySlug($request->get('slug'));
        if (!$post) {
            return new Response('Not found', Response::HTTP_NOT_FOUND);
        }

        $content = $this->twig->render('post.twig', ['post' => $post]);
        return new Response($content);
    }
}
