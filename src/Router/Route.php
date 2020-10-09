<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

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

    public function __construct(string $method, string $uri, View $view, $context)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->view = $view;
        $this->context = $context;
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
}