<?php

call_user_func(
    function ($argv) {


        /** @var \DI\Container $container */
        $container = require  'config/bootstrap.php';
        $class = $argv[1] ?? null;

        if (!$class) {
            exit("Enter command as first argument!");
        }

        $className = '\App\Console\\' . $class;

        try {
            $command = $container->get($className);
            $params = array_slice($argv, 2);
            $command(...$params);

        } catch (\Exception $exception) {
            exit('Error: ' . $exception->getMessage());
        }
        
    }, $argv
);

