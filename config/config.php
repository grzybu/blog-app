<?php

use function DI\create;

return [
    \App\Repository\Post\PostRepository::class       => create(\App\Repository\Post\InMemoryRepository::class),
    \Symfony\Component\HttpFoundation\Request::class => function () {
        return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    },
];