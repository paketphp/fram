<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\RouteCollector;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Helper\RouteHelper;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

final class FastRouteRouterTest extends TestCase
{
    /** @var FastRouteRouter */
    private $router;

    protected function setUp(): void
    {
        $this->router = new FastRouteRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/get', TestView::class);
        }));
    }

    public function testThatItTakesDispatcherAndReturnsNewRouteOnHit()
    {
        $route = RouteHelper::getRoute('GET', '/get');
        $newRoute = $this->router->route($route);
        $this->assertNotSame($route, $newRoute);
        $this->assertInstanceOf(TestView::class, $newRoute->getView());
        $this->assertIsArray($newRoute->getPayload());
    }

    public function testThatItTakesDispatcherAndReturnsSameRouteOnMiss()
    {
        $route = RouteHelper::getRoute('POST', '/get');
        $newRoute = $this->router->route($route);
        $this->assertSame($route, $newRoute);
        $this->assertInstanceOf(EmptyView::class, $newRoute->getView());
    }
}