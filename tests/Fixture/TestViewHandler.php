<?php
declare(strict_types=1);

namespace Paket\Fram\Fixture;

use Paket\Fram\Router\Route;
use Paket\Fram\View\View;
use Paket\Fram\ViewHandler\ViewHandler;

final class TestViewHandler implements ViewHandler
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

    public function handle(Route $route, View $view): Route
    {
        $callable = self::$callable;
        return $callable($route, $view);
    }

    public function getViewTypeClass(): string
    {
        return TestViewType::class;
    }
}