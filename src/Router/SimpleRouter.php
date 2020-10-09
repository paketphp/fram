<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\View;
use Paket\Fram\ViewFactory\ViewFactory;

final class SimpleRouter implements Router
{
    /** @var array */
    private $routes;
    /** @var string */
    private $viewFor404;
    /** @var ViewFactory */
    private $viewFactory;

    public function __construct(array $routes, string $viewFor404, ViewFactory $viewFactory)
    {
        $this->routes = $routes;
        $this->viewFor404 = $viewFor404;
        $this->viewFactory = $viewFactory;
    }

    public function route(string $method, string $uri): Route
    {
        if (empty($this->routes[$method]) || empty($this->routes[$method][$uri])) {
            $viewClass = $this->viewFor404;
        } else {
            $viewClass = $this->routes[$method][$uri];
        }

        $view = $this->viewFactory->build($viewClass);
        return new Route($method, $uri, $view, null);
    }
}