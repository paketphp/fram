<?php
declare(strict_types=1);

namespace Paket\Fram\View;

use Paket\Fram\Router\Route;

interface HtmlView extends View
{
    public function render(Route $route): void;
}