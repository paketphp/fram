<?php

namespace Paket\Fram\Examples\Common\ViewHandler;

use Paket\Fram\Examples\Common\Util\CsrfTokenService;
use Paket\Fram\Examples\Common\View\ErrorView;
use Paket\Fram\Router\Route;
use Paket\Fram\View\View;
use Paket\Fram\ViewHandler\ViewHandler;

final class FormBackendHandler implements ViewHandler
{
    /** @var CsrfTokenService */
    private $csrfTokenService;

    public function __construct(CsrfTokenService $csrfTokenService)
    {
        $this->csrfTokenService = $csrfTokenService;
    }

    public function handle(Route $route, View $view): Route
    {
        if (!$this->csrfTokenService->validate($_POST['token'] ?? '', $error)) {
            return $route->withViewClass(ErrorView::class, [400, $error]);
        }

        /** @var $view FormBackend */
        $newRoute = $view->render($route);
        return $newRoute !== null ? $newRoute : $route;
    }

    public function getViewInterface(): string
    {
        return FormBackend::class;
    }
}