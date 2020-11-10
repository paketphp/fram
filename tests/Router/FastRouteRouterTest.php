<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\RouteCollector;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\View\EmptyView;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

final class FastRouteRouterTest extends TestCase
{
    /** @var DefaultViewFactory */
    private static $viewFactory;

    /** @var FastRouteRouter */
    private $router;

    public static function setUpBeforeClass(): void
    {
        self::$viewFactory = new DefaultViewFactory();
    }

    protected function setUp(): void
    {
        $this->router = new FastRouteRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/get', TestView::class);
        }));
    }

    public function testThatItTakesDispatcherAndReturnsNewRouteOnHit()
    {
        $route = self::getRoute('GET', '/get');
        $newRoute = $this->router->route($route);
        $this->assertNotSame($route, $newRoute);
        $this->assertInstanceOf(TestView::class, $newRoute->getView());
        $this->assertIsArray($newRoute->getPayload());
    }

    public function testThatItTakesDispatcherAndReturnsSameRouteOnMiss()
    {
        $route = self::getRoute('POST', '/get');
        $newRoute = $this->router->route($route);
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