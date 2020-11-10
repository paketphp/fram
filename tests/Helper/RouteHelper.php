<?php
declare(strict_types=1);

namespace Paket\Fram\Helper;

use Paket\Fram\Router\Route;
use Paket\Fram\ViewFactory\DefaultViewFactory;

final class RouteHelper
{
    /** @var DefaultViewFactory */
    private static $viewFactory;

    public static function getRoute(string $method, string $uri): Route
    {
        if (empty(self::$viewFactory)) {
            self::$viewFactory = new DefaultViewFactory();
        }

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        return Route::create(self::$viewFactory);
    }
}