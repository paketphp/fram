<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Fram\Router\DefaultRoute;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\View\EmptyView;
use Paket\Fram\ViewHandler\ViewHandler;
use Psr\Container\ContainerInterface;
use Throwable;

final class Fram
{
    /** @var ContainerInterface */
    private $container;
    /** @var Router */
    private $router;
    /** @var string[] */
    private $viewHandlerClasses;

    public function __construct(ContainerInterface $container, Router $router, string ...$viewHandlerClasses)
    {
        $this->container = $container;
        $this->router = $router;
        $this->viewHandlerClasses = $viewHandlerClasses;
    }

    public function run(callable $cb): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $throwable = null;
        try {
            $routerRoute = $this->router->route($method, $uri);
        } catch (Throwable $t) {
            if ($t instanceof LogicException) {
                throw $t;
            }
            $throwable = $t;
            $routerRoute = new DefaultRoute($method, $uri, EmptyView::class);
        }

        $cbRoute = null;
        $newRoute = $routerRoute;
        while ($cbRoute !== $newRoute) {
            $cbRoute = $cb($newRoute, $throwable);
            if ($cbRoute === null) {
                break;
            }
            $throwable = null;
            try {
                $newRoute = $this->executeRoute($cbRoute);
            } catch (Throwable $t) {
                if ($t instanceof LogicException) {
                    throw $t;
                }
                $newRoute = $cbRoute;
                $cbRoute = null;
                $throwable = $t;
            }
        }
    }

    private function executeRoute(Route $route): Route
    {
        $viewClass = $route->getViewClass();
        $implements = class_implements($viewClass);

        foreach ($this->viewHandlerClasses as $viewHandlerClass) {
            /** @var ViewHandler $viewHandler */
            $viewHandler = $this->container->get($viewHandlerClass);
            if (in_array($viewHandler->getViewInterface(), $implements, true)) {
                $view = $this->container->get($viewClass);
                return $viewHandler->handle($route, $view);
            }
        }
        throw new LogicException("No View handler found for {$viewClass}");
    }
}