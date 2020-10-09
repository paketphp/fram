<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\SimpleView;

final class SimpleViewHandler implements ViewHandler
{
    public function handle(Route $route): void
    {
        /** @var $view SimpleView */
        $view = $route->getView();
        $view->render($route);
    }

    public function getViewClass(): string
    {
        return SimpleView::class;
    }
}