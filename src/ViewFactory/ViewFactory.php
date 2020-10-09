<?php
declare(strict_types=1);

namespace Paket\Fram\ViewFactory;

use Paket\Fram\View\View;

interface ViewFactory
{
    public function build(string $viewClass): View;

}