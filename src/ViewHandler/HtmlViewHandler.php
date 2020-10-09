<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\View\HtmlView;
use Paket\Fram\View\View;

final class HtmlViewHandler implements ViewHandler
{
    public function handle(View $view): void
    {
        header('Content-Type: text/html');
        /** @var $view HtmlView */
        $view->render();
    }

    public function getViewClass(): string
    {
        return HtmlView::class;
    }
}