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
use Paket\Fram\ViewHandler\SimpleViewHandler;
use Throwable;
use function FastRoute\simpleDispatcher;

final class FastRouteApplication
{
    public function run(): void
    {
        $router = new FastRouteRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', IndexView::PATH, IndexView::class);
            $r->addRoute('GET', NewNoteView::PATH, NewNoteView::class);
            $r->addRoute('GET', EditNoteView::PATH, EditNoteView::class);
            $r->addRoute('POST', NewNoteBackend::PATH, NewNoteBackend::class);
            $r->addRoute('POST', EditNoteBackend::PATH, EditNoteBackend::class);
            $r->addRoute('POST', DeleteNoteBackend::PATH, DeleteNoteBackend::class);
        }));
        $fram = new Fram(new BeroContainer(new StrictBero()), $router, new HtmlViewHandler(), new SimpleViewHandler());

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