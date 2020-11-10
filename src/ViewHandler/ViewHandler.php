<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Router\Route;

interface ViewHandler
{
    public function handle(Route $route): Route;

    public function getViewTypeClass(): string;
}