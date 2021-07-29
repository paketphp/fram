<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\EmptyView;

final class SimpleRouter implements Router
{
    /** @var array */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(string $method, string $uri): Route
    {
        if (isset($this->routes[$method], $this->routes[$method][$uri])) {
            return new Route($method, $uri, $this->routes[$method][$uri]);
        }
        return new Route($method, $uri, EmptyView::class);
    }
}