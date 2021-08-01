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

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @inheritdoc
     */
    public function getViewClass(): string
    {
        return $this->viewClass;
    }

    /**
     * @inheritdoc
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @inheritdoc
     */
    public function hasEmptyView(): bool
    {
        return $this->viewClass === EmptyView::class;
    }

    /**
     * @inheritdoc
     */
    public function getPastRoutes(): array
    {
        return $this->pastRoutes;
    }

    /**
     * @inheritdoc
     */
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