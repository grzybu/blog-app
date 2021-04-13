<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Clock;

class ClockTest extends TestCase
{

    protected function setUp(): void
    {
        Clock::release();
    }

    /**
     * @test
     */
    public function itCanReturnNow()
    {
        $now = Clock::now();
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);
    }

    /**
     * @test
     */
    public function itCanFreeze()
    {
        $now = Clock::now();

        Clock::freeze(\DateTimeImmutable::createFromFormat(Clock::DATE_FORMAT, date(Clock::DATE_FORMAT)));

        $this->assertEquals($now, Clock::now());
    }
}
