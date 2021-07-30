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
use Paket\Fram\Router\DefaultRoute;
use Paket\Fram\Router\Route;
use Paket\Fram\Router\Router;
use Paket\Fram\View\EmptyView;
use Paket\Fram\View\View;
use Paket\Fram\ViewHandler\ViewHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Throwable;

final class FramTest extends TestCase
{
    /** @var ContainerInterface */
    private $container;

    public function setUp(): void
    {
        $this->container = new BeroContainer(new StrictBero());
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/get?foo=bar';
    }

    public function testThatRouterGetsCorrectMethodAndUri()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            $expectedMethod = 'GET';
            $expectedUri = '/get';
            if ($expectedMethod !== $method) {
                throw new LogicException("Error: wrong method expected {$expectedMethod} got {$method}");
            }
            if ($expectedUri !== $uri) {
                throw new LogicException("Error: wrong uri expected {$expectedUri} got {$uri}");
            }

            $this->assertSame('GET11', $method);
            $this->assertSame('/ge1t', $uri);
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            $this->fail();
        }));;
        $fram->run(function (Route $route, ?Throwable $throwable) {
        });
    }

    public function testThatOnRouterMissRouteIsEmpty()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, EmptyView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            $this->fail();
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertTrue($route->hasEmptyView());
            $this->assertNull($throwable);
        });
    }

    public function testThatOnRouterHitRouteHasAView()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            $this->fail();
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertSame(TestView::class, $route->getViewClass());
            $this->assertFalse($route->hasEmptyView());
            $this->assertNull($throwable);
        });
    }

    public function testThatReturningRouteContinuesExecution()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            $this->assertTrue(true);
            return $route;
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatReturningEmptyViewThrowsLogicException()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, EmptyView::class);
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertSame(EmptyView::class, $route->getViewClass());
            return $route;
        });
    }

    public function testThatNotRegisteringViewHandlerThrowsLogicException()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatThrowingInRouterSetsThrowable()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            throw new RuntimeException();
        }), self::getViewHandler(function (Route $route) {
            $this->fail();
        }));

        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->assertTrue($route->hasEmptyView());
            $this->assertInstanceOf(RuntimeException::class, $throwable);
        });
    }

    public function testThatThrowingLogicExceptionInRouterThrows()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            throw new LogicException();
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            $this->fail();
        });
    }

    public function testThatThrowingInViewSetsThrowable()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
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
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            throw new LogicException();
        }));

        $this->expectException(LogicException::class);
        $fram->run(function (Route $route, ?Throwable $throwable) {
            return $route;
        });
    }

    public function testThatWhenViewThrowsKeepPreviousRoute()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, EmptyView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            throw new RuntimeException();
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
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, EmptyView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            if ($route->getViewClass() === TestView::class) {
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
                $this->assertSame(ThirdTestView::class, $route->getViewClass());
                $this->assertNull($throwable);
                return null;
            }

            $this->fail();
        });
        $this->assertSame($count, 3);
    }

    public function testThatReturningNewRouteFromViewCallsCallable()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, TestView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
            return $route->withViewClass(SecondTestView::class);
        }));

        $count = 0;
        $fram->run(function (Route $route, ?Throwable $throwable) use (&$count) {
            $count++;

            if ($count === 1) {
                $this->assertSame(TestView::class, $route->getViewClass());
                return $route;
            }

            if ($count === 2) {
                $this->assertSame(SecondTestView::class, $route->getViewClass());
                return null;
            }

            $this->fail();
        });
        $this->assertSame($count, 2);
    }

    public function testThatThrowingInCallableDoesBubble()
    {
        $fram = new Fram($this->container, self::getRouter(function (string $method, string $uri) {
            return new DefaultRoute($method, $uri, EmptyView::class);
        }), self::getViewHandler(function (Route $route, View $view) {
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

            public function route(string $method, string $uri): Route
            {
                $callable = $this->callable;
                return $callable($method, $uri);
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

            public function handle(Route $route, View $view): Route
            {
                $callable = $this->callable;
                return $callable($route, $view);
            }

            public function getViewTypeClass(): string
            {
                return TestViewType::class;
            }
        };
    }
}