<?php
declare(strict_types=1);

namespace Paket\Fram\ViewFactory;

use Paket\Bero\Bero;
use Paket\Fram\View\View;

class BeroViewFactory implements ViewFactory
{
    /** @var Bero */
    private $bero;

    public function __construct(Bero $bero)
    {
        $this->bero = $bero;
    }

    public function build(string $viewClass): View
    {
        /** @var View $view */
        $view = $this->bero->getObject($viewClass);
        return $view;
    }
}