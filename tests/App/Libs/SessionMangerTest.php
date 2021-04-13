<?php

namespace App\Tests\Libs;

use App\Libs\SessionManager;
use PHPUnit\Framework\TestCase;

class SessionMangerTest extends TestCase
{
    private function getSessionManager(string $name = 'MY_SESSION'): SessionManager
    {
        return new SessionManager($name);
    }

    /**
     * @runInSeparateProcess
     */
    public function testIsExpired(): void
    {
        $sessionManager = $this->getSessionManager();

        $this->assertEquals(false, $sessionManager->isExpired());

        $_SESSION['_last_activity'] = time() - 356 * 60 * 60;

        $this->assertEquals(true, $sessionManager->isExpired());
    }

    /**
     * @runInSeparateProcess
     */
    public function testIsValid(): void
    {
        $sessionManager = $this->getSessionManager();

        $this->assertEquals(true, $sessionManager->isValid());
    }


    /**
     * @runInSeparateProcess
     */
    public function testItCanStart(): void
    {
        $sessionManager = $this->getSessionManager();
        $this->assertEquals(true, $sessionManager->start());
    }

    /**
     * @runInSeparateProcess
     */
    public function testItRunSetupOnce(): void
    {
        $name1 = 'SESSION_STARTED';
        session_id($name1);
        $sessionManager = $this->getSessionManager('NEW_NAME');
        $this->assertEquals($name1, $sessionManager->getSessionId());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function testItCanStartOnce(): void
    {
        $sessionManager = $this->getSessionManager();
        $this->assertEquals(true, $sessionManager->start());
        $this->assertEquals(false, $sessionManager->start());
    }

    /**
     * @runInSeparateProcess
     */
    public function testItCanPutAndGet(): void
    {
        $sessionManager = $this->getSessionManager();

        $sessionManager->put('a', 'value-1');
        $this->assertEquals('value-1', $sessionManager->get('a'));
        $this->assertEquals(null, $sessionManager->get('b'));

        $sessionManager->put('Class.A', 'value-1');
        $this->assertEquals('value-1', $sessionManager->get('Class.A'));
    }


    /**
     * @runInSeparateProcess
     */
    public function testCanRefresh(): void
    {
        $sessionManager = $this->getSessionManager();
        $sessionManager->put('a', 'value-1');
        $this->assertEquals(true, $sessionManager->refresh());
        $this->assertEquals('value-1', $sessionManager->get('a'));
    }


    /**
     * @test
     * @runInSeparateProcess
     */
    public function testCanForget(): void
    {
        $sessionManager = $this->getSessionManager();
        $sessionManager->put('a', 'value-1');
        $this->assertEquals(true, $sessionManager->forget());
        $sessionManager = $this->getSessionManager();
        $this->assertEquals(false, $sessionManager->forget());
    }
}
