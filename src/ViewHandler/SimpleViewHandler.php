<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;
use Paket\Fram\View\View;

final class SimpleViewHandler implements ViewHandler
{
    public function handle(Route $route, View $view): Route
    {
        /** @var $view SimpleView */
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewTypeClass(): string
    {
        return SimpleView::class;
    }
}