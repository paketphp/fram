<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Fixture\SimpleTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Router\DefaultRoute;
use Paket\Fram\View\SimpleView;
use PHPUnit\Framework\TestCase;

final class SimpleViewHandlerTest extends TestCase
{
    /** @var SimpleViewHandler */
    private $viewHandler;

    protected function setUp(): void
    {
        $this->viewHandler = new SimpleViewHandler();
        SimpleTestView::reset();
    }

    public function testThatGetViewClassReturnsCorrectViewClass()
    {
        $this->assertSame(SimpleView::class, $this->viewHandler->getViewTypeClass());
    }

    public function testHandleRendersViewWithCorrectRoute()
    {
        SimpleTestView::set(function ($viewRoute) use (&$route) {
            $this->assertSame($route, $viewRoute);
        });
        $route = new DefaultRoute('GET', '/get', SimpleTestView::class);

        $this->viewHandler->handle($route, new SimpleTestView());
    }

    public function testHandleReturnsSameRouteWhenViewDoesNotReturnRoute()
    {
        SimpleTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new DefaultRoute('GET', '/get', SimpleTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new SimpleTestView());

        $this->assertSame($route, $returnedRoute);
    }

    public function testHandleReturnsNewRouteFromView()
    {
        SimpleTestView::set(function ($viewRoute) {
            return $viewRoute->withViewClass(TestView::class);
        });
        $route = new DefaultRoute('GET', '/get', SimpleTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new SimpleTestView());

        $this->assertNotSame($route, $returnedRoute);
        $this->assertSame(TestView::class, $returnedRoute->getViewClass());
    }
}