<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

final class SimpleRouter implements Router
{
    /** @var array */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(Route $route): Route
    {
        $method = $route->getMethod();
        $uri = $route->getUri();
        if (isset($this->routes[$method], $this->routes[$method][$uri])) {
            return $route->withViewClass($this->routes[$method][$uri]);
        }
        return $route;
    }
}