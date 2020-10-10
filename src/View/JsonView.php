<?php
declare(strict_types=1);

namespace Paket\Fram\View;

use Paket\Fram\Router\Route;

interface JsonView extends View
{
    /**
     * @param Route $route
     * @return Route|void
     */
    public function render(Route $route);
}