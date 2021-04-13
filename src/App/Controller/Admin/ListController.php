<?php

namespace App\Controller\Admin;

use App\Repository\Posts\PostRepository;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListController
{
    protected PostRepository $repository;
    protected Environment $twig;
    protected AuthService $authService;
    public function __construct(PostRepository $repository, Environment $twig, AuthService $authService)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->authService = $authService;
    }

    public function __invoke(Request $request): Response
    {
        if (!$this->authService->isAuthenticated()) {
            return new RedirectResponse('/login?returnTo=' . base64_encode($request->getPathInfo()));
        }

        $posts = $this->repository->getAll();

        $content = $this->twig->render('/admin/list.twig', ['posts' => $posts]);
        return new Response($content);
    }
}
