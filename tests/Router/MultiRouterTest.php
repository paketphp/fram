<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Helper\RouteHelper;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;

final class MultiRouterTest extends TestCase
{
    public function testThatItGoesThruRoutersInOrder()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $firstRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route->withViewClass(TestView::class);
            }
        };

        $secondRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route->withViewClass(SecondTestView::class);
            }
        };

        $multiRouter = new MultiRouter($firstRouter, $secondRouter);
        $newRoute = $multiRouter->route($route);

        $this->assertNotSame($route, $newRoute);
        $this->assertInstanceOf(TestView::class, $newRoute->getView());
    }

    public function testThatIfFirstRouterIsEmptyItTriesTheNextRouter()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $firstRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route;
            }
        };

        $secondRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route->withViewClass(SecondTestView::class);
            }
        };

        $multiRouter = new MultiRouter($firstRouter, $secondRouter);
        $newRoute = $multiRouter->route($route);

        $this->assertNotSame($route, $newRoute);
        $this->assertInstanceOf(SecondTestView::class, $newRoute->getView());
    }

    public function testThatIfBothRoutersAreEmptyReturnEmpty()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $firstRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route;
            }
        };

        $secondRouter = new class implements Router {
            public function route(Route $route): Route
            {
                return $route;
            }
        };

        $multiRouter = new MultiRouter($firstRouter, $secondRouter);
        $newRoute = $multiRouter->route($route);

        $this->assertSame($route, $newRoute);
        $this->assertInstanceOf(EmptyView::class, $newRoute->getView());
    }
}