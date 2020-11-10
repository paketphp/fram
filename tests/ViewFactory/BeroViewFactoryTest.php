<?php
declare(strict_types=1);

namespace Paket\Fram\ViewFactory;

use Paket\Bero\StrictBero;
use Paket\Fram\Fixture\TestView;
use PHPUnit\Framework\TestCase;

final class BeroViewFactoryTest extends TestCase
{
    public function testThatItInstantiatesView()
    {
        $bero = new StrictBero();
        $viewFactory = new BeroViewFactory($bero);
        $view = $viewFactory->build(TestView::class);
        $this->assertInstanceOf(TestView::class, $view);
    }
}