<?php
declare(strict_types=1);

namespace Paket\Fram\ViewHandler;

use Paket\Fram\Fixture\TestView;
use Paket\Fram\Helper\RouteHelper;
use Paket\Fram\Router\Route;
use Paket\Fram\View\JsonView;
use Paket\Fram\View\SimpleView;
use PHPUnit\Framework\TestCase;

final class JsonViewHandlerTest extends TestCase
{
    /** @var JsonViewHandler */
    private $viewHandler;

    protected function setUp(): void
    {
        $this->viewHandler = new JsonViewHandler();
        HeaderFunction::reset();
    }

    public function testThatGetViewClassReturnsCorrectViewClass()
    {
        $this->assertSame(JsonView::class, $this->viewHandler->getViewTypeClass());
    }

    public function testHandleRendersViewWithCorrectRoute()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $newRoute = $route->withView(new class(function ($route) use (&$newRoute) {
            $this->assertSame($newRoute, $route);
        }) implements SimpleView {

            /** @var callable */
            private $callable;

            public function __construct(callable $callable)
            {
                $this->callable = $callable;
            }

            public function render(Route $route)
            {
                $callable = $this->callable;
                $callable($route);
            }
        });

        $this->viewHandler->handle($newRoute);
    }

    public function testHandleReturnsSameRouteWhenDoesNotReturnRoute()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $newRoute = $route->withView(new class implements SimpleView {
            public function render(Route $route)
            {
            }
        });

        $returnedRoute = $this->viewHandler->handle($newRoute);
        $this->assertSame($newRoute, $returnedRoute);
    }

    public function testHandleReturnsRouteFromView()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $newRoute = $route->withView(new class implements SimpleView {
            public function render(Route $route)
            {
                return $route->withViewClass(TestView::class);
            }
        });

        $returnedRoute = $this->viewHandler->handle($newRoute);
        $this->assertNotSame($newRoute, $returnedRoute);
        $this->assertInstanceOf(TestView::class, $returnedRoute->getView());
    }


    public function testHandleSetsCorrectHeader()
    {
        $route = RouteHelper::getRoute('GET', '/get');

        $newRoute = $route->withView(new class implements SimpleView {
            public function render(Route $route)
            {
            }
        });

        $this->viewHandler->handle($newRoute);
        $this->assertSame('Content-Type: application/json', HeaderFunction::get());
    }
}