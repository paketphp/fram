<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Bero\Container\BeroContainer;
use Paket\Bero\StrictBero;
use Paket\Fram\Examples\Common\View\NotFoundView;
use Paket\Fram\Examples\Common\View\ExceptionView;
use Paket\Fram\Fram;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\SimpleRouter;
use Paket\Fram\ViewHandler\HtmlViewHandler;
use Paket\Fram\ViewHandler\DefaultViewHandler;
use Throwable;

final class SimpleApplication
{
    public function run(): void
    {
        $router = new SimpleRouter(
            [
                'GET' => [
                    IndexView::PATH => IndexView::class,
                    NewNoteView::PATH => NewNoteView::class,
                    EditNoteView::PATH => EditNoteView::class,
                ],
                'POST' => [
                    NewNoteBackend::PATH => NewNoteBackend::class,
                    EditNoteBackend::PATH => EditNoteBackend::class,
                    DeleteNoteBackend::PATH => DeleteNoteBackend::class,
                ]
            ]);
        $fram = new Fram(new BeroContainer(new StrictBero()), $router, DefaultViewHandler::class, HtmlViewHandler::class);

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