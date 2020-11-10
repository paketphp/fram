<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\View;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

class View404 implements HtmlView
{
    public function render(Route $route)
    {
        http_response_code(404);
        ?>
        <html>
        <head>
            <title>404 - Error</title>
        </head>
        <body>
        <h1>404 - Error</h1>
        <p>No such route</p>
        </body>
        </html>
        <?php
    }
}