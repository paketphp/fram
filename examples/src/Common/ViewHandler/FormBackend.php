<?php

namespace Paket\Fram\Examples\Common\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\View;

interface FormBackend extends View
{
    /**
     * @param Route $route
     * @return Route|void
     */
    public function render(Route $route);
}