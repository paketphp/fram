<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Fixture\JsonTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Router\DefaultRoute;
use Paket\Fram\View\JsonView;
use PHPUnit\Framework\TestCase;

final class JsonViewHandlerTest extends TestCase
{
    /** @var JsonViewHandler */
    private $viewHandler;

    protected function setUp(): void
    {
        $this->viewHandler = new JsonViewHandler();
        JsonTestView::reset();
        HeaderFunction::reset();
    }

    public function testThatGetViewClassReturnsCorrectViewClass()
    {
        $this->assertSame(JsonView::class, $this->viewHandler->getViewInterface());
    }

    public function testHandleRendersViewWithCorrectRoute()
    {
        JsonTestView::set(function ($viewRoute) use (&$route) {
            $this->assertSame($route, $viewRoute);
        });
        $route = new DefaultRoute('GET', '/get', JsonTestView::class);

        $this->viewHandler->handle($route, new JsonTestView());
    }

    public function testHandleReturnsSameRouteWhenViewDoesNotReturnRoute()
    {
        JsonTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new DefaultRoute('GET', '/get', JsonTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new JsonTestView());

        $this->assertSame($route, $returnedRoute);
    }

    public function testHandleReturnsNewRouteFromView()
    {
        JsonTestView::set(function ($viewRoute) {
            return $viewRoute->withViewClass(TestView::class);
        });
        $route = new DefaultRoute('GET', '/get', JsonTestView::class);

        $returnedRoute = $this->viewHandler->handle($route, new JsonTestView());

        $this->assertNotSame($route, $returnedRoute);
        $this->assertSame(TestView::class, $returnedRoute->getViewClass());
    }

    public function testHandleSetsCorrectHeader()
    {
        JsonTestView::set(function ($viewRoute) use (&$route) {
        });
        $route = new DefaultRoute('GET', '/get', JsonTestView::class);

        $this->viewHandler->handle($route, new JsonTestView());
        $this->assertSame('Content-Type: application/json', HeaderFunction::get());
    }
}