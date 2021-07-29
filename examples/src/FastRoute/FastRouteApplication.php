<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\FastRoute;

use FastRoute\RouteCollector;
use Paket\Bero\Container\BeroContainer;
use Paket\Bero\StrictBero;
use Paket\Fram\Examples\Common\View\View404;
use Paket\Fram\Examples\Common\View\View500;
use Paket\Fram\Fram;
use Paket\Fram\Router\FastRouteRouter;
use Paket\Fram\Router\Route;
use Paket\Fram\ViewHandler\HtmlViewHandler;
use Throwable;
use function FastRoute\simpleDispatcher;

final class FastRouteApplication
{
    public function run(): void
    {
        $router = new FastRouteRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addGroup('/fastroute', function (RouteCollector $r) {
                $r->addRoute('GET', '/', IndexView::class);
            });
        }));
        $fram = new Fram(new BeroContainer(new StrictBero()), $router, new HtmlViewHandler());

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                return $route->withViewClass(View500::class, $throwable);
            }

            if ($route->hasEmptyView()) {
                return $route->withViewClass(View404::class);
            }
            return $route;
        });
    }
}