<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\View\JsonView;
use Paket\Fram\View\View;

final class JsonViewHandler implements ViewHandler
{
    public function handle(View $view): void
    {
        header('Content-Type: application/json');
        /** @var $view JsonView */
        $view->render();
    }

    public function getViewClass(): string
    {
        return JsonView::class;
    }
}