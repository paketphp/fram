<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\EmptyView;

final class DefaultRoute implements Route
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

    public function __construct(string $method, string $uri, string $viewClass, $payload = null)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->viewClass = $viewClass;
        $this->payload = $payload;
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
}