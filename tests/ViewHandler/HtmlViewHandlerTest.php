<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Fixture\HtmlTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Router\Route;
use Paket\Fram\View\HtmlView;
use PHPUnit\Framework\TestCase;

final class HtmlViewHandlerTest extends TestCase
{
    /** @var HtmlViewHandler */
    private $viewHandler;

    protected function setUp(): void
    {
        $this->viewHandler = new HtmlViewHandler();
        HtmlTestView::reset();
        HeaderFunction::reset();
    }

    public function testThatGetViewClassReturnsCorrectViewClass()
    {
        $this->assertSame(HtmlView::class, $this->viewHandler->getViewTypeClass());
    }

    public function testHandleRendersViewWithCorrectRoute()
    {
        HtmlTestView::set(function ($viewRoute) use (&$route) {
            $this->assertSame($route, $viewRoute);
        });
        $route = new Route('GET', '/get', HtmlTestView::class);

        $this->viewHandler->handle($route, new HtmlTestView());
    }

    public function testHandleReturnsSameRouteWhenViewDoesNotReturnRoute()
    {
        HtmlTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new Route('GET', '/get', HtmlTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new HtmlTestView());

        $this->assertSame($route, $returnedRoute);
    }

    public function testHandleReturnsRouteFromView()
    {
        HtmlTestView::set(function ($viewRoute) {
            return $viewRoute->withViewClass(TestView::class);
        });
        $route = new Route('GET', '/get', HtmlTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new HtmlTestView());

        $this->assertNotSame($route, $returnedRoute);
        $this->assertSame(TestView::class, $returnedRoute->getViewClass());
    }

    public function testHandleSetsCorrectHeader()
    {
        HtmlTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new Route('GET', '/get', HtmlTestView::class);

        $this->viewHandler->handle($route, new HtmlTestView());

        $this->assertSame('Content-Type: text/html', HeaderFunction::get());
    }
}