<?php

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */

call_user_func(
    function () {
        $container = require dirname(__DIR__) . '/config/bootstrap.php';

        $environment = getenv('ENV');
        /**
         * Register the error handler
         */
        $whoops = new \Whoops\Run;
        if ($environment !== 'production') {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        } else {
            $whoops->pushHandler(
                function ($e) {
                    echo 'Something went wrong ;(';
                }
            );
        }
        $whoops->register();

        $dispatcher = FastRoute\simpleDispatcher(
            function (\FastRoute\RouteCollector $r) {
                $r->addRoute('GET', '/', 'App\Controller\HomeController');
                $r->addRoute('GET', '/{page:\d+}', 'App\Controller\HomeController');
                $r->addRoute('GET', '/post/{slug}', 'App\Controller\PostController');
                $r->addRoute(['GET', 'POST'], '/login', 'App\Controller\Auth\LoginController');
                $r->addRoute('GET', '/logout', 'App\Controller\Auth\LogoutController');
                $r->addRoute('GET', '/admin', 'App\Controller\Admin\ListController');
                $r->addRoute(['POST', 'GET'], '/admin/post/create', 'App\Controller\Admin\NewPostController');
                $r->addRoute(['POST', 'GET'], '/admin/post/{id:\d+}', 'App\Controller\Admin\EditPostController');
            }
        );

        /** @var \App\Libs\SessionManager $sessionManager */
        $sessionManager = $container->get('SessionManager');
        $sessionManager->start();
        if (!$sessionManager->isValid()) {
            $sessionManager->forget();
        }

        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $container->get(\Symfony\Component\HttpFoundation\Request::class);
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->setContent('404 - Page not found');
                $response->setStatusCode(404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->setContent('405 - Method not allowed');
                $response->setStatusCode(405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $controller = $routeInfo[1];
                $requestParams = $routeInfo[2];
                $request->query->add($requestParams);

                /** @var \Symfony\Component\HttpFoundation\Response $response */
                $response = $container->get($controller)($request);
                $response->send();
                break;
        }
    });