<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\ViewHandler\ViewHandler;

final class Fram
{
    /** @var Router */
    private $router;
    /** @var ViewHandler[] */
    private $handlers;

    public function __construct(Router $router, ViewHandler ...$handlers)
    {
        $this->router = $router;
        $this->handlers = $handlers;
    }

    public function run(): Route
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $route = Route::create($_SERVER['REQUEST_METHOD'], $uri);
        $route = $this->router->route($route);
        if ($route->hasEmptyView()) {
            return $route;
        }
        $this->executeRoute($route);
        return $route;
    }

    public function executeRoute(Route $route): void
    {
        $view = $route->getView();
        $implements = class_implements($view);

        foreach ($this->handlers as $handler) {
            if (in_array($handler->getViewClass(), $implements, true)) {
                $handler->handle($route);
                return;
            }
        }
        throw new LogicException('No View handler found for ' . get_class($view));
    }
}