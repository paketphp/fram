<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class HtmlViewHandler implements ViewHandler
{
    public function handle(Route $route): void
    {
        header('Content-Type: text/html');
        /** @var $view HtmlView */
        $view = $route->getView();
        $view->render($route);
    }

    public function getViewClass(): string
    {
        return HtmlView::class;
    }
}