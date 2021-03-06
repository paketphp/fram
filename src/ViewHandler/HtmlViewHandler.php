<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class HtmlViewHandler implements ViewHandler
{
    public function handle(Route $route): Route
    {
        header('Content-Type: text/html');
        /** @var $view HtmlView */
        $view = $route->getView();
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewTypeClass(): string
    {
        return HtmlView::class;
    }
}