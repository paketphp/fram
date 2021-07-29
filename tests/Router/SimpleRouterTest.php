<?php
declare(strict_types=1);

namespace Paket\Fram\Router;

use Paket\Fram\Fixture\TestView;
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
        $route = $this->router->route('GET', '/get');
        $this->assertSame(TestView::class, $route->getViewClass());
    }

    public function testThatItReturnsEmptyRouteOnMethodHitAndUriMiss()
    {
        $route = $this->router->route('GET', '/miss');
        $this->assertSame(EmptyView::class, $route->getViewClass());
    }

    public function testThatItReturnsEmptyRouteOnMethodMissAndUriHit()
    {
        $route = $this->router->route('POST', '/get');
        $this->assertSame(EmptyView::class, $route->getViewClass());
    }
}