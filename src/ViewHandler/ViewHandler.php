<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\View;

interface ViewHandler
{
    /**
     * Handle a $route and a $view, where $view is an instantiation of
     * $route->getViewClass()
     *
     * @param Route $route
     * @param View $view
     * @return Route
     */
    public function handle(Route $route, View $view): Route;

    /**
     * Which View interface this ViewHandler can handle
     *
     * @return string
     */
    public function getViewInterface(): string;
}