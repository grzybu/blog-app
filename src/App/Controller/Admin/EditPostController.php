<?php

namespace App\Controller\Admin;

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
        $id = $request->get('id');
        $post = $id ? $this->repository->get($id) : null;


        if ($request->getMethod() === Request::METHOD_POST) {


        }


        $content = $this->twig->render('/admin/post.twig', ['post' => $post ?? null]);
        return new Response($content);
    }

}