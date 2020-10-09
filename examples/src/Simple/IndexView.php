<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Simple;

use Paket\Fram\View\HtmlView;

final class IndexView implements HtmlView
{
    public function render(): void
    {
        ?>
        <html>
        <head>
            <title>Simple</title>
        </head>
        <body>
        <h1>Welcome to Simple example</h1>
        </body>
        </html>
        <?php
    }
}