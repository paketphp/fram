<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\Dispatcher;
use Paket\Fram\View\EmptyView;

final class FastRoute implements Route
{
    /** @var string */
    private $method;
    /** @var string */
    private $uri;
    /** @var string */
    private $viewClass;
    /** @var mixed */
    private $payload;
    /** @var Route[] */
    private $pastRoutes = [];
    /** @var array */
    private $routeInfo;
    /** @var array */
    private $vars = [];

    public function __construct(string $method, string $uri, string $viewClass, array $routeInfo)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->viewClass = $viewClass;
        $this->routeInfo = $routeInfo;
        if ($routeInfo[0] === Dispatcher::FOUND) {
            $this->vars = $routeInfo[2];
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getViewClass(): string
    {
        return $this->viewClass;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    public function hasEmptyView(): bool
    {
        return $this->viewClass === EmptyView::class;
    }

    /**
     * @return Route[]
     */
    public function getPastRoutes(): array
    {
        return $this->pastRoutes;
    }

    public function withViewClass(string $viewClass, $payload = null): Route
    {
        $route = clone $this;
        $route->viewClass = $viewClass;
        if (func_num_args() === 2) {
            $route->payload = $payload;
        }
        $route->pastRoutes[] = $this;
        return $route;
    }

    /**
     * Returns FastRoute routeInfo array
     *
     * @return array
     */
    public function getRouteInfo(): array
    {
        return $this->routeInfo;
    }

    /**
     * Returns the vars part of routeInfo
     *
     * @return array
     */
    public function getRouteVars(): array
    {
        return $this->vars;
    }

    /**
     * Get one var by name of routeInfo
     *
     * @param string $name
     * @return string|null
     */
    public function getRouteVar(string $name): ?string
    {
        return $this->vars[$name] ?? null;
    }
}