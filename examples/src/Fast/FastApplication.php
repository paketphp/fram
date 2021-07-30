<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Fast;

use FastRoute\RouteCollector;
use Paket\Bero\Container\BeroContainer;
use Paket\Bero\StrictBero;
use Paket\Fram\Examples\Common\View\NotFoundView;
use Paket\Fram\Examples\Common\View\ExceptionView;
use Paket\Fram\Fram;
use Paket\Fram\Router\FastRouter;
use Paket\Fram\Router\Route;
use Paket\Fram\ViewHandler\HtmlViewHandler;
use Paket\Fram\ViewHandler\DefaultViewHandler;
use Throwable;
use function FastRoute\simpleDispatcher;

final class FastApplication
{
    public function run(): void
    {
        $router = new FastRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', IndexView::PATH, IndexView::class);
            $r->addRoute('GET', NewNoteView::PATH, NewNoteView::class);
            $r->addRoute('GET', EditNoteView::PATH, EditNoteView::class);
            $r->addRoute('POST', NewNoteBackend::PATH, NewNoteBackend::class);
            $r->addRoute('POST', EditNoteBackend::PATH, EditNoteBackend::class);
            $r->addRoute('POST', DeleteNoteBackend::PATH, DeleteNoteBackend::class);
        }));
        $fram = new Fram(new BeroContainer(new StrictBero()), $router, HtmlViewHandler::class, DefaultViewHandler::class);

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                return $route->withViewClass(ExceptionView::class, $throwable);
            }

            if ($route->hasEmptyView()) {
                return $route->withViewClass(NotFoundView::class);
            }
            return $route;
        });
    }
}