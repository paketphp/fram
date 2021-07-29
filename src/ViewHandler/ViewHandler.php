<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;
use Paket\Fram\View\View;

interface ViewHandler
{
    public function handle(Route $route, View $view): Route;

    public function getViewTypeClass(): string;
}