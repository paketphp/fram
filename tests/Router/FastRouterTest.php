<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use FastRoute\Dispatcher;
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
            $r->addRoute('GET', '/get/{id}', TestView::class);
        }));
    }

    public function testThatItReturnsCorrectViewOnRouteHit()
    {
        /** @var FastRoute $route */
        $route = $this->router->route('GET', '/get/1');
        $routeInfo = $route->getRouteInfo();

        $this->assertSame(Dispatcher::FOUND, $routeInfo[0]);
        $this->assertSame(TestView::class, $route->getViewClass());
    }

    public function testThatRouteVarsIsSetOnRouteHit()
    {
        /** @var FastRoute $route */
        $route = $this->router->route('GET', '/get/1');

        $this->assertSame(['id' => '1'], $route->getRouteVars());
        $this->assertSame('1', $route->getRouteVar('id'));
    }

    public function testThatItReturnsMethodNotAllowedOnWrongMethodHit()
    {
        /** @var FastRoute $route */
        $route = $this->router->route('POST', '/get/1');
        $routeInfo = $route->getRouteInfo();

        $this->assertSame(Dispatcher::METHOD_NOT_ALLOWED, $routeInfo[0]);
        $this->assertSame(['GET'], $routeInfo[1]);
        $this->assertSame(EmptyView::class, $route->getViewClass());
    }

    public function testThatItReturnsNotFoundOnRouteMiss()
    {
        /** @var FastRoute $route */
        $route = $this->router->route('GET', '/get');
        $routeInfo = $route->getRouteInfo();

        $this->assertSame(Dispatcher::NOT_FOUND, $routeInfo[0]);
        $this->assertSame(EmptyView::class, $route->getViewClass());
    }
}