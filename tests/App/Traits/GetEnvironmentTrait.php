<?php

namespace App\Tests\Traits;

use Twig\Environment;

trait GetEnvironmentTrait
{

    protected function getEnvironment(): Environment
    {
        $loader = new \Twig\Loader\FilesystemLoader(TESTS_ROOT . '/src/templates');
        $environment =  new Environment($loader);

        return $environment;
    }
}
