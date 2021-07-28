<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\EmptyView;
use Paket\Fram\View\View;
use Psr\Container\ContainerInterface;

final class Route
{
    /** @var ContainerInterface */
    private static $container;
    /** @var string */
    private $method;
    /** @var string */
    private $uri;
    /** @var View */
    private $view;
    /** @var mixed */
    private $payload;
    /** @var Route[] */
    private $pastRoutes = [];

    private function __construct(string $method, string $uri, View $view)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->view = $view;
    }

    public static function create(ContainerInterface $container): self
    {
        self::$container = $container;
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $emptyView = $container->get(EmptyView::class);
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
    public function getPayload()
    {
        return $this->payload;
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

    public function withView(View $view, $payload = null): self
    {
        $route = clone $this;
        $route->view = $view;
        if (func_num_args() === 2) {
            $route->payload = $payload;
        }
        $route->pastRoutes[] = $this;
        return $route;
    }

    public function withViewClass(string $viewClass, $payload = null): self
    {
        $route = clone $this;
        $route->view = self::$container->get($viewClass);
        if (func_num_args() === 2) {
            $route->payload = $payload;
        }
        $route->pastRoutes[] = $this;
        return $route;
    }
}