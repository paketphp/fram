<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\ViewFactory\ViewFactory;

final class SimpleRouter implements Router
{
    /** @var array */
    private $routes;
    /** @var ViewFactory */
    private $viewFactory;

    public function __construct(array $routes, ViewFactory $viewFactory)
    {
        $this->routes = $routes;
        $this->viewFactory = $viewFactory;
    }

    public function route(Route $route): Route
    {
        $method = $route->getMethod();
        $uri = $route->getUri();
        if (isset($this->routes[$method], $this->routes[$method][$uri])) {
            $view = $this->viewFactory->build($this->routes[$method][$uri]);
            return $route->withView($view);
        }
        return $route;
    }
}