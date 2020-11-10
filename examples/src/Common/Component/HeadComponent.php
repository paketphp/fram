<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Component;

use Paket\Fram\Examples\Common\Util\Html;

final class HeadComponent
{
    public function render(string $title)
    {
        ?>
        <html>
        <head>
            <title><?= Html::escape($title) ?></title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        </head>
        <body>
        <?php
    }

}