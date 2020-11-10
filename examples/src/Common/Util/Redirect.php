<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Util;

final class Redirect
{
    public static function reply(string $url): void
    {
        header('Location: ' . $url, true, 303);
        exit;
    }
}