<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\View\SimpleView;
use Paket\Fram\View\View;

final class SimpleViewHandler implements ViewHandler
{
    public function handle(View $view): void
    {
        /** @var $view SimpleView */
        $view->render();
    }

    public function getViewClass(): string
    {
        return SimpleView::class;
    }
}