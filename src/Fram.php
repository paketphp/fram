<?php
declare(strict_types=1);

namespace Paket\Fram;

use Paket\Fram\Router\Router;
use Paket\Fram\ViewHandler\ViewHandler;

final class Fram
{
    /** @var Router */
    private $router;
    /** @var ViewHandler[] */
    private $handlers;

    public function __construct(Router $router, ViewHandler ...$handlers)
    {
        $this->router = $router;
        $this->handlers = $handlers;
    }

    public function run(): bool
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $view = $this->router->route($_SERVER['REQUEST_METHOD'], $uri);
        $implements = class_implements($view);

        foreach ($this->handlers as $handler) {
            if (in_array($handler->getViewClass(), $implements, true)) {
                $handler->handle($view);
                return true;
            }
        }
        return false;
    }
}