<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

interface Router
{
    public function route(Route $route): Route;
}