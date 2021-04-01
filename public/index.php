<?php

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
call_user_func(function () {
    require dirname(__DIR__) . '/config/bootstrap.php';

    $environment = 'development';
    /**
     * Register the error handler
     */
    $whoops = new \Whoops\Run;
    if ($environment !== 'production') {
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    } else {
        $whoops->pushHandler(function($e){
            echo 'Something went wrong ;(';
        });
    }
    $whoops->register();

    die("Blog app");
});