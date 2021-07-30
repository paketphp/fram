<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\RouteCollector;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\View\EmptyView;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

final class FastRouterTest extends TestCase
{
    /** @var FastRouter */
    private $router;

    protected function setUp(): void
    {
        $this->router = new FastRouter(simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/get', TestView::class);
        }));
    }

    public function testThatItTakesDispatcherAndReturnsNewRouteOnHit()
    {
        $route = $this->router->route('GET', '/get');
        $this->assertSame(TestView::class, $route->getViewClass());
        $this->assertIsArray($route->getPayload());
    }

    public function testThatItTakesDispatcherAndReturnsEmptyRouteOnMiss()
    {
        $route = $this->router->route('POST', '/get');
        $this->assertSame(EmptyView::class, $route->getViewClass());
        $this->assertNull($route->getPayload());
    }
}