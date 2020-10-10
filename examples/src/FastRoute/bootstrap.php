<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Paket\Fram\Examples\Common\View404;
use Paket\Fram\Examples\FastRoute\IndexView;
use Paket\Fram\Fram;
use Paket\Fram\Router\FastRouteRouter;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\HtmlViewHandler;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new FastRouteRouter(FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addGroup('/fastroute', function (RouteCollector $r) {
        $r->addRoute('GET', '/', IndexView::class);
    });
}));
$fram = new Fram(new DefaultViewFactory(), $router, new HtmlViewHandler());

try {
    $route = $fram->run();
    if ($route->hasEmptyView()) {
        $fram->executeRoute($route->withViewClass(View404::class));
    }
} catch (Throwable $throwable) {
    http_response_code(500);
    echo $throwable->getMessage();
}