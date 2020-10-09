<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\View\View;

interface ViewHandler
{
    public function handle(View $view): void;

    public function getViewClass(): string;
}