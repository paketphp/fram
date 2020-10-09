<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\JsonView;

final class JsonViewHandler implements ViewHandler
{
    public function handle(Route $route): void
    {
        header('Content-Type: application/json');
        /** @var $view JsonView */
        $view = $route->getView();
        $view->render($route);
    }

    public function getViewClass(): string
    {
        return JsonView::class;
    }
}