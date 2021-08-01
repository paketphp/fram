<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\DefaultView;
use Paket\Fram\View\View;

final class DefaultViewHandler implements ViewHandler
{
    public function handle(Route $route, View $view): Route
    {
        /** @var $view DefaultView */
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewInterface(): string
    {
        return DefaultView::class;
    }
}