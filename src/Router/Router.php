<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

interface Router
{
    public function route(string $method, string $uri): Route;
}