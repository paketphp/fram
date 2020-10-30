<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\ViewFactory\ViewFactory;
use Paket\Fram\ViewHandler\ViewHandler;
use Throwable;

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

    public function run(callable $cb): void
    {
        $initRoute = Route::create($this->viewFactory);
        try {
            $routerRoute = $this->router->route($initRoute);
        } catch (Throwable $throwable) {
            $routerRoute = $initRoute;
            $routerRoute->setThrowable($throwable);
        }

        $cbRoute = null;
        $newRoute = $routerRoute;
        next:
        try {
            while ($cbRoute !== $newRoute) {
                $cbRoute = $cb($newRoute);
                if ($cbRoute === null) {
                    break;
                }
                $newRoute = $this->executeRoute($cbRoute);
            }
        } catch (Throwable $throwable) {
            $newRoute->setThrowable($throwable);
            goto next;
        }
    }

    private function executeRoute(Route $route): Route
    {
        $view = $route->getView();
        $implements = class_implements($view);

        foreach ($this->handlers as $handler) {
            if (in_array($handler->getViewClass(), $implements, true)) {
                return $handler->handle($route);
            }
        }
        throw new LogicException('No View handler found for ' . get_class($view));
    }
}