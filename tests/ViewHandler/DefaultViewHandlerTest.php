<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Fixture\DefaultTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Router\DefaultRoute;
use Paket\Fram\View\DefaultView;
use PHPUnit\Framework\TestCase;

final class DefaultViewHandlerTest extends TestCase
{
    /** @var DefaultViewHandler */
    private $viewHandler;

    protected function setUp(): void
    {
        $this->viewHandler = new DefaultViewHandler();
        DefaultTestView::reset();
    }

    public function testThatGetViewClassReturnsCorrectViewClass()
    {
        $this->assertSame(DefaultView::class, $this->viewHandler->getViewTypeClass());
    }

    public function testHandleRendersViewWithCorrectRoute()
    {
        DefaultTestView::set(function ($viewRoute) use (&$route) {
            $this->assertSame($route, $viewRoute);
        });
        $route = new DefaultRoute('GET', '/get', DefaultTestView::class);

        $this->viewHandler->handle($route, new DefaultTestView());
    }

    public function testHandleReturnsSameRouteWhenViewDoesNotReturnRoute()
    {
        DefaultTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new DefaultRoute('GET', '/get', DefaultTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new DefaultTestView());

        $this->assertSame($route, $returnedRoute);
    }

    public function testHandleReturnsNewRouteFromView()
    {
        DefaultTestView::set(function ($viewRoute) {
            return $viewRoute->withViewClass(TestView::class);
        });
        $route = new DefaultRoute('GET', '/get', DefaultTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new DefaultTestView());

        $this->assertNotSame($route, $returnedRoute);
        $this->assertSame(TestView::class, $returnedRoute->getViewClass());
    }
}