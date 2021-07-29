<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Fixture\ThirdTestView;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testThatCreateNewRouteHasCorrectMethodUriViewPayloadAnsHistory()
    {
        $route = new Route('GET', '/get', TestView::class);
        $this->assertSame('GET', $route->getMethod());
        $this->assertSame('/get', $route->getUri());
        $this->assertEmpty($route->getPayload());
        $this->assertSame(TestView::class, $route->getViewClass());
        $this->assertFalse($route->hasEmptyView());
        $this->assertEmpty($route->getPastRoutes());
    }

    public function testThatCreateNewRoutePlusPayloadHasCorrectMethodUriViewPayloadAnsHistory()
    {
        $route = new Route('GET', '/get', TestView::class, 17);
        $this->assertSame('GET', $route->getMethod());
        $this->assertSame('/get', $route->getUri());
        $this->assertSame(17, $route->getPayload());
        $this->assertSame(TestView::class, $route->getViewClass());
        $this->assertFalse($route->hasEmptyView());
        $this->assertEmpty($route->getPastRoutes());
    }

    public function testThatCreateNewRouteWithEmptyViewHasEmptyView()
    {
        $route = new Route('GET', '/get', EmptyView::class);
        $this->assertTrue($route->hasEmptyView());
    }


    public function testThatMakingNewRouteKeepsMethodAndUriTheSame()
    {
        $route = new Route('GET', '/get', TestView::class);
        $newRoute = $route->withViewClass(SecondTestView::class);
        $this->assertSame($route->getMethod(), $newRoute->getMethod());
        $this->assertSame($route->getUri(), $newRoute->getUri());
    }

    public function testThatMakingNewRouteMakesRouteNoLongerAnEmptyView()
    {
        $route = new Route('GET', '/get', EmptyView::class);
        $newRoute = $route->withViewClass(TestView::class);
        $this->assertFalse($newRoute->hasEmptyView());
    }

    public function testThatAddingPayloadWithViewClassIsStored()
    {
        $route = new Route('GET', '/get', TestView::class);
        $newRoute = $route->withViewClass(SecondTestView::class, 'payload');
        $this->assertSame('payload', $newRoute->getPayload());
    }

    public function testThatAddingNewPayloadWithViewClassOverwritesExistingPayload()
    {
        $route = new Route('GET', '/get', TestView::class);
        $newRoute = $route->withViewClass(SecondTestView::class, 'payload');
        $newNewRoute = $newRoute->withViewClass(ThirdTestView::class, 'newPayload');
        $this->assertSame('newPayload', $newNewRoute->getPayload());
    }

    public function testThatNotAddingPayloadWhenPayloadExistKeepsPayloadWithViewClass()
    {
        $route = new Route('GET', '/get', TestView::class);
        $newRoute = $route->withViewClass(SecondTestView::class, 'payload');
        $newNewRoute = $newRoute->withViewClass(ThirdTestView::class);
        $this->assertSame('payload', $newNewRoute->getPayload());
    }

    public function testThatMakingNewRouteStoresPreviousRoutes()
    {
        $route = new Route('GET', '/get', TestView::class);
        $newRoute = $route->withViewClass(SecondTestView::class);
        $newNewRoute = $newRoute->withViewClass(ThirdTestView::class);

        $this->assertCount(1, $newRoute->getPastRoutes());
        $this->assertSame($route, $newRoute->getPastRoutes()[0]);

        $this->assertCount(2, $newNewRoute->getPastRoutes());
        $this->assertSame($route, $newNewRoute->getPastRoutes()[0]);
        $this->assertSame($newRoute, $newNewRoute->getPastRoutes()[1]);
    }
}