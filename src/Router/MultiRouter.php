<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

final class MultiRouter implements Router
{
    /** @var Router[] */
    private $routers;

    public function __construct(Router ...$routers)
    {
        $this->routers = $routers;
    }

    public function route(Route $route): Route
    {
        foreach ($this->routers as $router) {
            $route = $router->route($route);
            if (!$route->hasEmptyView()) {
                return $route;
            }
        }
        return $route;
    }
}