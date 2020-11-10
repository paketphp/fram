<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Util;

final class Html
{
    public static function escape($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
