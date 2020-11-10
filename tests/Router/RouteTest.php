<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Helper\RouteHelper;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testThatInitialRouteHasCorrectMethodUriViewPayloadAnsHistory()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $this->assertSame('GET', $route->getMethod());
        $this->assertSame('/get', $route->getUri());
        $this->assertEmpty($route->getPayload());
        $this->assertInstanceOf(EmptyView::class, $route->getView());
        $this->assertTrue($route->hasEmptyView());
        $this->assertEmpty($route->getPastRoutes());
    }

    public function testThatInitialRouteRemovesQueryParameters()
    {
        $route = RouteHelper::getRoute('GET', '/get?foo=bar');
        $this->assertSame('/get', $route->getUri());
    }

    public function testThatMakingNewRouteKeepsMethodAndUriTheSame()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class);
        $this->assertSame($route->getMethod(), $newRoute->getMethod());
        $this->assertSame($route->getUri(), $newRoute->getUri());
    }

    public function testThatMakingNewRouteMakesRouteNoLongerAnEmptyView()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class);
        $this->assertFalse($newRoute->hasEmptyView());
    }

    public function testThatAddingPayloadWithViewIsStored()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withView(new TestView(), 'payload');
        $this->assertSame('payload', $newRoute->getPayload());
    }

    public function testThatAddingNewPayloadWithViewOverwritesExistingPayload()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class, 'payload');
        $newNewRoute = $newRoute->withView(new SecondTestView(), 'newPayload');
        $this->assertSame('newPayload', $newNewRoute->getPayload());
    }

    public function testThatNotAddingPayloadWhenPayloadExistKeepsPayloadWithView()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class, 'payload');
        $newNewRoute = $newRoute->withView(new SecondTestView());
        $this->assertSame('payload', $newNewRoute->getPayload());
    }

    public function testThatAddingPayloadWithViewClassIsStored()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class, 'payload');
        $this->assertSame('payload', $newRoute->getPayload());
    }

    public function testThatAddingNewPayloadWithViewClassOverwritesExistingPayload()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class, 'payload');
        $newNewRoute = $newRoute->withViewClass(SecondTestView::class, 'newPayload');
        $this->assertSame('newPayload', $newNewRoute->getPayload());
    }

    public function testThatNotAddingPayloadWhenPayloadExistKeepsPayloadWithViewClass()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class, 'payload');
        $newNewRoute = $newRoute->withViewClass(SecondTestView::class);
        $this->assertSame('payload', $newNewRoute->getPayload());
    }

    public function testThatMakingNewRouteStoresPreviousRoutes()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $route->withViewClass(TestView::class);
        $newNewRoute = $newRoute->withViewClass(SecondTestView::class);

        $this->assertCount(1, $newRoute->getPastRoutes());
        $this->assertSame($route, $newRoute->getPastRoutes()[0]);

        $this->assertCount(2, $newNewRoute->getPastRoutes());
        $this->assertSame($route, $newNewRoute->getPastRoutes()[0]);
        $this->assertSame($newRoute, $newNewRoute->getPastRoutes()[1]);
    }
}