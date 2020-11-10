<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;

final class SimpleViewHandler implements ViewHandler
{
    public function handle(Route $route): Route
    {
        /** @var $view SimpleView */
        $view = $route->getView();
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewTypeClass(): string
    {
        return SimpleView::class;
    }
}