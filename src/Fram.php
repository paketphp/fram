<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\ViewFactory\ViewFactory;
use Paket\Fram\ViewHandler\ViewHandler;

final class Fram
{
    /** @var ViewFactory */
    private $viewFactory;
    /** @var Router */
    private $router;
    /** @var ViewHandler[] */
    private $handlers;

    public function __construct(ViewFactory $viewFactory, Router $router, ViewHandler ...$handlers)
    {
        $this->viewFactory = $viewFactory;
        $this->router = $router;
        $this->handlers = $handlers;
    }

    public function run(): Route
    {
        $route = Route::create($this->viewFactory);
        $newRoute = $this->router->route($route);
        if ($newRoute->hasEmptyView()) {
            return $route;
        }
        return $this->executeRoute($newRoute);
    }

    public function executeRoute(Route $route): Route
    {
        $view = $route->getView();
        $implements = class_implements($view);

        foreach ($this->handlers as $handler) {
            if (in_array($handler->getViewClass(), $implements, true)) {
                $newRoute = $handler->handle($route);
                return $newRoute === $route ? $newRoute : $this->executeRoute($newRoute);
            }
        }
        throw new LogicException('No View handler found for ' . get_class($view));
    }
}