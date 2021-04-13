<?php

namespace App\Utils;

class Clock
{
    private static ?\DateTimeImmutable $frozen = null;
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    public static function release(): void
    {
        self::$frozen = null;
    }

    public static function freeze(\DateTimeImmutable $now = null): \DateTimeImmutable
    {
        self::$frozen = $now ?? \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, date(self::DATE_FORMAT));
        return self::$frozen;
    }

    public static function now(): \DateTimeImmutable
    {
        if (self::$frozen) {
            return self::$frozen;
        }
        return \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, date(self::DATE_FORMAT));
    }
}
