<?php
declare(strict_types=1);

namespace Paket\Fram\Helper;

use Paket\Bero\Container\BeroContainer;
use Paket\Bero\StrictBero;
use Paket\Fram\Router\Route;
use Psr\Container\ContainerInterface;

final class RouteHelper
{
    /** @var ContainerInterface */
    private static $container;

    public static function getRoute(string $method, string $uri): Route
    {
        if (empty(self::$container)) {
            self::$container = new BeroContainer(new StrictBero());
        }

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        return Route::create(self::$container);
    }
}