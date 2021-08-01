<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

interface Router
{
    /**
     * Return Route based on $method and $uri with a viewClass set,
     * on miss return with EmptyView set
     *
     * @param string $method
     * @param string $uri
     * @return Route
     */
    public function route(string $method, string $uri): Route;
}