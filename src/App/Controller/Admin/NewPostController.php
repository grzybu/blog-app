<?php

namespace App\Controller\Admin;

use App\Repository\Posts\PostRepository;
use App\Service\AuthService;
use App\Utils\Clock;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class EditPostController
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
        $id = $request->get('id');
        $post = $id ? $this->repository->get($id) : null;

        if (!$post) {
            return new Response('Post not found', Response::HTTP_NOT_FOUND);
        }

        $updated = null;

        if ($request->getMethod() === Request::METHOD_POST) {
            $data = $request->request->all();
            $data['user_id'] = $this->authService->getUserId();
            $post->fromArray($data);

            try {
                $this->repository->save($post);
                $updated = true;
            } catch (\Exception $e) {
                $updated = false;
            }
        }


        $content = $this->twig->render('/admin/post.twig', ['post' => $post ?? null, 'updated' => $updated]);
        return new Response($content);
    }
}
