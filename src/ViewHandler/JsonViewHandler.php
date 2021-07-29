<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\JsonView;
use Paket\Fram\View\View;

final class JsonViewHandler implements ViewHandler
{
    public function handle(Route $route, View $view): Route
    {
        header('Content-Type: application/json');
        /** @var $view JsonView */
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewTypeClass(): string
    {
        return JsonView::class;
    }
}