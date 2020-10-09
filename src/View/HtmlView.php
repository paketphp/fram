<?php
declare(strict_types=1);

namespace Paket\Fram\View;

interface HtmlView extends View
{
    public function render(): void;
}