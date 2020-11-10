<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Fixture\TestViewType;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\ViewFactory\DefaultViewFactory;
use Paket\Fram\ViewHandler\ViewHandler;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

final class FramTest extends TestCase
{
    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/get';
    }

    public function testThatOnRouterMissRouteIsEmpty()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route;
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(false);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertTrue($route->hasEmptyView());
            $this->assertNull($throwable);
        });
    }

    public function testThatOnRouterHitRouteHasAView()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(false);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertInstanceOf(TestView::class, $route->getView());
            $this->assertFalse($route->hasEmptyView());
            $this->assertNull($throwable);
        });
    }

    public function testThatReturningRouteContinuesExecution()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(true);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatReturningEmptyRouteSetsThrowable()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route;
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(false);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                $this->assertInstanceOf(LogicException::class, $throwable);
                return null;
            }

            return $route;
        });
    }

    public function testThatNotRegisteringViewHandlerSetsThrowable()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                $this->assertInstanceOf(LogicException::class, $throwable);
                return null;
            }

            return $route;
        });
    }


    public function testThatThrowingInRouterSetsThrowable()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            throw new RuntimeException();
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(false);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                $this->assertTrue($route->hasEmptyView());
                $this->assertInstanceOf(RuntimeException::class, $throwable);
                return null;
            }

            return $route;
        });
    }

    public function testThatThrowingInViewSetsThrowable()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            throw new RuntimeException();
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            if (isset($throwable)) {
                $this->assertInstanceOf(RuntimeException::class, $throwable);
                return null;
            }

            return $route;
        });
    }

    public function testThatReturningNewRouteFromViewCallsCallable()
    {
        $fram = new Fram(new DefaultViewFactory(), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            return $route->withViewClass(SecondTestView::class);
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            static $count = 0;
            $count++;

            if ($count === 1) {
                $this->assertInstanceOf(TestView::class, $route->getView());
                return $route;
            }

            if ($count === 2) {
                $this->assertInstanceOf(SecondTestView::class, $route->getView());
                return null;
            }

            $this->assertTrue(false);
        });
    }


    private static function getRouter(callable $callable): Router
    {
        return new class($callable) implements Router {

            /** @var callable */
            private $callable;

            public function __construct(callable $callable)
            {
                $this->callable = $callable;
            }

            public function route(Route $route): Route
            {
                $callable = $this->callable;
                return $callable($route);
            }
        };
    }

    private static function getViewHandler(callable $callable): ViewHandler
    {
        return new class($callable) implements ViewHandler {

            /** @var callable */
            private $callable;

            public function __construct(callable $callable)
            {
                $this->callable = $callable;
            }

            public function handle(Route $route): Route
            {
                $callable = $this->callable;
                return $callable($route);
            }

            public function getViewTypeClass(): string
            {
                return TestViewType::class;
            }
        };
    }
}