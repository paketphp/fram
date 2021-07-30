<?php

namespace Paket\Fram\Router;

interface Route
{
    public function getMethod(): string;

    public function getUri(): string;

    public function getViewClass(): string;

    /**
     * @return mixed
     */
    public function getPayload();

    public function hasEmptyView(): bool;

    /**
     * @return Route[]
     */
    public function getPastRoutes(): array;

    public function withViewClass(string $viewClass, $payload = null): Route;
}