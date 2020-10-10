<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\Dispatcher;
use Paket\Fram\ViewFactory\ViewFactory;

class FastRouteRouter implements Router
{
    /** @var Dispatcher */
    private $dispatcher;
    /** @var ViewFactory */
    private $viewFactory;

    public function __construct(Dispatcher $dispatcher, ViewFactory $viewFactory)
    {
        $this->dispatcher = $dispatcher;
        $this->viewFactory = $viewFactory;
    }

    public function route(Route $route): Route
    {
        $routeInfo = $this->dispatcher->dispatch($route->getMethod(), $route->getUri());
        if ($routeInfo[0] === Dispatcher::FOUND) {
            $view = $this->viewFactory->build($routeInfo[1]);
            return $route->withView($view, $routeInfo);
        }
        return $route;
    }
}