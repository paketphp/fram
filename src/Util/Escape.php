<?php
declare(strict_types=1);

namespace Paket\Fram\Util;

final class Escape
{
    public static function html($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
