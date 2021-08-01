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

    /**
     * @inheritdoc
     */
    public function route(string $method, string $uri): Route
    {
        if (isset($this->routes[$method], $this->routes[$method][$uri])) {
            return new DefaultRoute($method, $uri, $this->routes[$method][$uri]);
        }
        return new DefaultRoute($method, $uri, EmptyView::class);
    }
}