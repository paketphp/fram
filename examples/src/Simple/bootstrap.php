<?php
declare(strict_types=1);

use Paket\Fram\Examples\Common\View404;
use Paket\Fram\Examples\Simple\IndexView;
use Paket\Fram\Fram;
use Paket\Fram\Router\SimpleRouter;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\HtmlViewHandler;

require __DIR__ . '/../../../vendor/autoload.php';

$router = new SimpleRouter(
    ['GET' => [
        '/simple' => IndexView::class
    ]], View404::class, new DefaultViewFactory()
);
$fram = new Fram($router, new HtmlViewHandler());

try {
    $found = $fram->run();
    if (!$found) {
        throw new RuntimeException("No View handler found");
    }
} catch (Throwable $throwable) {
    http_response_code(500);
    echo $throwable->getMessage();
}