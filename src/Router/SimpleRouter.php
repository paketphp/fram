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

    public function route(string $method, string $uri): View
    {
        if (empty($this->routes[$method])) {
            return $this->viewFactory->build($this->viewFor404);
        }

        if (empty($this->routes[$method][$uri])) {
            return $this->viewFactory->build($this->viewFor404);
        }

        return $this->viewFactory->build($this->routes[$method][$uri]);
    }
}