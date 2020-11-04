<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;
use Throwable;

class View500 implements HtmlView
{
    public function render(Route $route)
    {
        $message = $route->getPayload() instanceof Throwable ? $route->getPayload()->getMessage() : '';
        http_response_code(500);
        ?>
        <html>
        <head>
            <title>500 - Error</title>
        </head>
        <body>
        <h1>500 - Error</h1>
        <p><?= $message ?></p>
        </body>
        </html>
        <?php
    }
}