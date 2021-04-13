<?php

namespace App\Controller\Admin;

use App\Repository\Posts\PostRepository;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Model\Post;

class NewPostController
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


        $error = null;
        if ($request->getMethod() === Request::METHOD_POST) {
            $data = $request->request->all();
            $data['user_id'] = $this->authService->getUserId();
            $post = (new Post())->fromArray($data);
            try {
                $post = $this->repository->save($post);
                return new RedirectResponse('/admin/post/' . $post->getId());
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $content = $this->twig->render('/admin/post.twig', ['error' => $error]);
        return new Response($content);
    }
}
