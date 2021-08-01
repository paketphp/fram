<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;
use Paket\Fram\View\View;

final class HtmlViewHandler implements ViewHandler
{
    public function handle(Route $route, View $view): Route
    {
        header('Content-Type: text/html');
        /** @var $view HtmlView */
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewInterface(): string
    {
        return HtmlView::class;
    }
}