<?php
declare(strict_types=1);

use Paket\Fram\Examples\Common\View404;
use Paket\Fram\Examples\Common\View500;
use Paket\Fram\Examples\Simple\IndexView;
use Paket\Fram\Fram;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\SimpleRouter;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\HtmlViewHandler;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new SimpleRouter(
    ['GET' => [
        '/simple/' => IndexView::class
    ]]);
$fram = new Fram(new DefaultViewFactory(), $router, new HtmlViewHandler());

$fram->run(function (Route $route, ?Throwable $throwable) {
    if (isset($throwable)) {
        return $route->withViewClass(View500::class, $throwable);
    }

    if ($route->hasEmptyView()) {
        return $route->withViewClass(View404::class);
    }
    return $route;
});