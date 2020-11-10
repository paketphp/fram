<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\View\EmptyView;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use PHPUnit\Framework\TestCase;

final class MultiRouterTest extends TestCase
{
    /** @var DefaultViewFactory */
    private static $viewFactory;

    /** @var FastRouteRouter */
    private $router;

    public static function setUpBeforeClass(): void
    {
        self::$viewFactory = new DefaultViewFactory();
    }

    public function testThatItGoesThruRoutersInOrder()
    {
        $route = self::getRoute('GET', '/get');

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
        $route = self::getRoute('GET', '/get');

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
        $route = self::getRoute('GET', '/get');

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


    private static function getRoute(string $method, string $uri): Route
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        return Route::create(self::$viewFactory);
    }
}