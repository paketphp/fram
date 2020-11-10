<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\TestView;
use Paket\Fram\Helper\RouteHelper;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;

final class SimpleRouterTest extends TestCase
{
    /** @var SimpleRouter */
    private $router;

    protected function setUp(): void
    {
        $this->router = new SimpleRouter(
            ['GET' => [
                '/get' => TestView::class
            ]]);
    }

    public function testThatItReturnsNewRouteOnMethodAndUriHit()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $this->router->route($route);
        $this->assertNotSame($route, $newRoute);
        $this->assertInstanceOf(TestView::class, $newRoute->getView());
    }

    public function testThatItReturnsSameRouteOnMethodHitAndUriMiss()
    {
        $route = RouteHelper::getRoute('GET', '/miss');
        $newRoute = $this->router->route($route);
        $this->assertSame($route, $newRoute);
        $this->assertInstanceOf(EmptyView::class, $newRoute->getView());
    }

    public function testThatItReturnsSameRouteOnMethodMissAndUriHit()
    {
        $route = RouteHelper::getRoute('POST', '/get');
        $newRoute = $this->router->route($route);
        $this->assertSame($route, $newRoute);
        $this->assertInstanceOf(EmptyView::class, $newRoute->getView());
    }
}