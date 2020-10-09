<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\FastRoute;

use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;

final class IndexView implements HtmlView
{
    public function render(Route $route): void
    {
        ?>
        <html>
        <head>
            <title>FastRoute</title>
        </head>
        <body>
        <h1>Welcome to FastRoute example</h1>
        </body>
        </html>
        <?php
    }
}