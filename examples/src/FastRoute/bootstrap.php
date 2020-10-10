<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Paket\Fram\Examples\Common\View404;
use Paket\Fram\Examples\FastRoute\IndexView;
use Paket\Fram\Fram;
use Paket\Fram\Router\FastRouteRouter;
use Paket\Fram\View\View;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\HtmlViewHandler;

require __DIR__ . '/../../../vendor/autoload.php';

$viewFactory = new DefaultViewFactory();
$router = new FastRouteRouter(FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addGroup('/fastroute', function (RouteCollector $r) {
        $r->addRoute('GET', '/', IndexView::class);
    });
}), $viewFactory);
$fram = new Fram($router, new HtmlViewHandler());

try {
    $route = $fram->run();
    if ($route->hasEmptyView()) {
        $view404 = $viewFactory->build(View404::class);
        $route404 = $route->withView($view404);
        $fram->executeRoute($route404);
    }
} catch (Throwable $throwable) {
    http_response_code(500);
    echo $throwable->getMessage();
}