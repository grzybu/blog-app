<?php

namespace App\Tests\Model;

use App\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testFromArrayAndToArray(): void
    {

        $data = [
            'id'         => 1,
            'username' => 'user',
            'password' => 'password'
        ];

        $model = (new User())->fromArray($data);

        $this->assertEquals(1, $model->getId());
        $this->assertEquals('user', $model->getUsername());
        $this->assertEquals('password', $model->getPassword());

        $this->assertEquals($data, $model->toArray());
    }
}
