<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\Dispatcher;
use Paket\Fram\View\EmptyView;

class FastRouteRouter implements Router
{
    /** @var Dispatcher */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function route(string $method, string $uri): Route
    {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);
        if ($routeInfo[0] === Dispatcher::FOUND) {
            return new Route($method, $uri, $routeInfo[1], $routeInfo);
        }
        return new Route($method, $uri, EmptyView::class);
    }
}