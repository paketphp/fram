<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\View\View;

interface Router
{
    public function route(string $method, string $uri): View;
}