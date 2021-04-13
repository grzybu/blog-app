<?php

namespace App\Controller;

use App\Repository\Posts\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController
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
        $page = $request->get('page', 1);
        $limit = 2;
        $posts = $this->repository->getAll($limit, $page);
        $total = $this->repository->getTotal();

        $totalPages = $total > 0 ? (int) \ceil($total / $limit) : 1;
        $content = $this->twig->render(
            'homepage.twig',
            [
                'posts'       => $posts,
                'currentPage' => $page,
                'totalPages'  => $totalPages,
            ]
        );
        return new Response($content);
    }
}
