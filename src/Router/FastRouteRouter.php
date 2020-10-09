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
    /** @var string */
    private $viewFor404;

    public function __construct(Dispatcher $dispatcher, string $viewFor404, ViewFactory $viewFactory)
    {
        $this->dispatcher = $dispatcher;
        $this->viewFor404 = $viewFor404;
        $this->viewFactory = $viewFactory;
    }

    public function route(string $method, string $uri): Route
    {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);
        if ($routeInfo[0] === Dispatcher::FOUND) {
            $viewClass = $routeInfo[1];
        } else {
            $viewClass = $this->viewFor404;
        }

        $view = $this->viewFactory->build($viewClass);
        return new Route($method, $uri, $view, $routeInfo);
    }
}