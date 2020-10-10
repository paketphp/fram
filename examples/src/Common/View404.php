<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

class View404 implements HtmlView
{
    public function render(Route $route)
    {
        ?>
        <html>
        <head>
            <title>404 - Error</title>
        </head>
        <body>
        <h1>404 - Error</h1>
        </body>
        </html>
        <?php
    }
}