<?php
declare(strict_types=1);

use Paket\Fram\Examples\Common\View404;
use Paket\Fram\Examples\Simple\IndexView;
use Paket\Fram\Fram;
use Paket\Fram\Router\SimpleRouter;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\HtmlViewHandler;

require __DIR__ . '/../../../vendor/autoload.php';

$viewFactory = new DefaultViewFactory();
$router = new SimpleRouter(
    ['GET' => [
        '/simple/' => IndexView::class
    ]], $viewFactory);
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