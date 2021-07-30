<?php
declare(strict_types=1);

namespace Paket\Fram\Fixture;

use Paket\Fram\Router\Route;
use Paket\Fram\View\DefaultView;

final class DefaultTestView implements DefaultView
{
    private static $callable;

    public static function set(callable $callable): void
    {
        self::$callable = $callable;
    }

    public static function reset(): void
    {
        self::$callable = null;
    }

    public function render(Route $route)
    {
        $callable = self::$callable;
        return $callable($route);
    }
}