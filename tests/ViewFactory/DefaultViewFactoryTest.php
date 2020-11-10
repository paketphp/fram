<?php
declare(strict_types=1);

namespace Paket\Fram\ViewFactory;

use Paket\Fram\Fixture\TestView;
use PHPUnit\Framework\TestCase;

final class DefaultViewFactoryTest extends TestCase
{
    public function testThatItInstantiatesView()
    {
        $viewFactory = new DefaultViewFactory();
        $view = $viewFactory->build(TestView::class);
        $this->assertInstanceOf(TestView::class, $view);
    }
}