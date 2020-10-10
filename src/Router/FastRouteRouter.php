<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\Dispatcher;

class FastRouteRouter implements Router
{
    /** @var Dispatcher */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function route(Route $route): Route
    {
        $routeInfo = $this->dispatcher->dispatch($route->getMethod(), $route->getUri());
        if ($routeInfo[0] === Dispatcher::FOUND) {
            return $route->withViewClass($routeInfo[1], $routeInfo);
        }
        return $route;
    }
}