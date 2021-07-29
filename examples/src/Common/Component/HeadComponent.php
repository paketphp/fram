<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

use Paket\Fram\Util\Escape;

final class HeadComponent
{
    public function render(string $title): void
    {
        ?>
        <html>
        <head>
            <title><?= Escape::html($title) ?></title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        </head>
        <body>
        <?php
    }

}