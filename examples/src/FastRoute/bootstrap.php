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
}), View404::class, new DefaultViewFactory());
$fram = new Fram($router, new HtmlViewHandler());

try {
    $found = $fram->run();
    if (!$found) {
        throw new LogicException('No View handler found');
    }
} catch (Throwable $throwable) {
    http_response_code(500);
    echo $throwable->getMessage();
}