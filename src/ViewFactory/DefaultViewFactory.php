<?php
declare(strict_types=1);

namespace Paket\Fram\ViewFactory;

use Paket\Fram\View\View;

final class DefaultViewFactory implements ViewFactory
{
    public function build(string $viewClass): View
    {
        return new $viewClass();
    }
}