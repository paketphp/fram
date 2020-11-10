<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

final class HeaderFunction
{
    /** @var string|null */
    private static $header;

    public static function get(): ?string
    {
        return self::$header;
    }

    public static function set(?string $header): void
    {
        self::$header = $header;
    }

    public static function reset(): void
    {
        self::set(null);
    }
}

function header(string $header)
{
    HeaderFunction::set($header);
}