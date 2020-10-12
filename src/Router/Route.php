<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\EmptyView;
use Paket\Fram\View\View;
use Paket\Fram\ViewFactory\ViewFactory;

final class Route
{
    /** @var ViewFactory */
    private static $viewFactory;
    /** @var string */
    private $method;
    /** @var string */
    private $uri;
    /** @var View */
    private $view;
    /** @var mixed */
    private $context;
    /** @var Route[] */
    private $pastRoutes = [];

    private function __construct(string $method, string $uri, View $view)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->view = $view;
    }

    public static function create(ViewFactory $viewFactory): self
    {
        self::$viewFactory = $viewFactory;
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $emptyView = $viewFactory->build(EmptyView::class);
        return new self($_SERVER['REQUEST_METHOD'], $uri, $emptyView);
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

    /**
     * @return mixed
     */
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

    public function withViewClass(string $viewClass, $context = null): self
    {
        $route = clone $this;
        $route->view = self::$viewFactory->build($viewClass);
        if (func_num_args() === 2) {
            $route->context = $context;
        }
        $route->pastRoutes[] = $this;
        return $route;
    }
}