<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

interface Route
{
    /**
     * Returns the HTTP method capitalized
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Returns the uri without hostname and query parameters but with a leading slash
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Returns the current view class
     *
     * @return string
     */
    public function getViewClass(): string;

    /**
     * User defined payload data
     *
     * @return mixed
     */
    public function getPayload();

    /**
     * Is the view class the empty view, the null view
     *
     * @return bool
     */
    public function hasEmptyView(): bool;

    /**
     * Returns previous Route objects with the newest first, but without the current this one,
     * primarily for debugging
     *
     * @return Route[]
     */
    public function getPastRoutes(): array;

    /**
     * Change view class and set optional payload, returns a new Route
     *
     * @param string $viewClass
     * @param null $payload
     * @return Route
     */
    public function withViewClass(string $viewClass, $payload = null): Route;
}