<?php
declare(strict_types=1);

namespace Paket\Fram;

use LogicException;
use Paket\Bero\Container\BeroContainer;
use Paket\Bero\StrictBero;
use Paket\Fram\Fixture\SecondTestView;
use Paket\Fram\Fixture\TestView;
use Paket\Fram\Fixture\TestViewType;
use Paket\Fram\Fixture\ThirdTestView;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\View\EmptyView;
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
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
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
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
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
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            $this->assertTrue(true);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatReturningEmptyViewThrowsLogicException()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route;
        }));

        $this->expectException(LogicException::class);;
        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertInstanceOf(EmptyView::class, $route->getView());
            return $route;
        });
    }

    public function testThatNotRegisteringViewHandlerThrowsLogicException()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatThrowingInRouterSetsThrowable()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
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

    public function testThatThrowingLogicExceptionInRouterThrows()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            throw new LogicException();
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatThrowingInViewSetsThrowable()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
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

    public function testThatThrowingLogicExceptionInViewThrows()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            throw new LogicException();
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatWhenViewThrowsKeepPreviousRoute()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route;
        }), self::getViewHandler(function (Route $route) {
            if ($route->getView() instanceof TestView) {
                throw new RuntimeException();
            }
            $this->assertFalse(true);
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            static $newRoute;

            if ($route->hasEmptyView()) {
                return $newRoute = $route->withViewClass(TestView::class);
            }

            $this->assertSame($newRoute, $route);
        });
    }

    public function testThatThrowableGetResetBetweenRuns()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route;
        }), self::getViewHandler(function (Route $route) {
            if ($route->getView() instanceof TestView) {
                throw new RuntimeException();
            }
            return $route->withViewClass(ThirdTestView::class);
        }));

        $count = 0;
        $fram->run(function (Route $route, ?Throwable $throwable) use (&$count) {
            $count++;

            if ($count === 1) {
                return $route->withViewClass(TestView::class);
            }

            if ($count === 2) {
                $this->assertInstanceOf(RuntimeException::class, $throwable);
                return $route->withViewClass(SecondTestView::class);
            }

            if ($count === 3) {
                $this->assertNull($throwable);
                return null;
            }

            $this->assertTrue(false);
        });
        $this->assertSame($count, 3);
    }

    public function testThatReturningNewRouteFromViewCallsCallable()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route->withViewClass(TestView::class);
        }), self::getViewHandler(function (Route $route) {
            return $route->withViewClass(SecondTestView::class);
        }));

        $count = 0;
        $fram->run(function (Route $route, ?Throwable $throwable) use (&$count) {
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
        $this->assertSame($count, 2);
    }

    public function testThatThrowingInCallableDoesBubble()
    {
        $fram = new Fram(new BeroContainer(new StrictBero()), self::getRouter(function (Route $route) {
            return $route;
        }), self::getViewHandler(function (Route $route) {
            return $route;
        }));

        $this->expectException(RuntimeException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            throw new RuntimeException();
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