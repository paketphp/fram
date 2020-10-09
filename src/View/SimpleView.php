<?php
declare(strict_types=1);

namespace Paket\Fram\View;

interface SimpleView extends View
{
    public function render(): void;
}