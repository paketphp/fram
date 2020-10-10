<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\EmptyView;
use Paket\Fram\View\View;

final class Route
{
    /** @var string */
    private $method;
    /** @var string */
    private $uri;
    /** @var View */
    private $view;
    private $context;
    /** @var Route[] */
    private $pastRoutes = [];

    private function __construct(string $method, string $uri, View $view)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->view = $view;
    }

    public static function create(string $method, string $uri): self
    {
        return new self($method, $uri, new EmptyView());
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getView(): View
    {
        return $this->view;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function hasEmptyView(): bool
    {
        return get_class($this->view) === EmptyView::class;
    }

    /**
     * @return Route[]
     */
    public function getPastRoutes(): array
    {
        return $this->pastRoutes;
    }

    public function withView(View $view, $context = null): self
    {
        $route = clone $this;
        $route->view = $view;
        $route->context = $context;
        $route->pastRoutes[] = $this;
        return $route;
    }
}