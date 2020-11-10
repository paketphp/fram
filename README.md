# Fram

Fram (_Swedish_ in front) is a view framework for PHP. Fram's goal is to fit everywhere, new projects, existing projects,
side by side with other frameworks, thus Fram is a flexible framework that can fit into many scenarios.

Fram is a view framework only, based on a route a view can be rendered. Other things necessary for a project like database handling, authentication, template rendering or logging, is not within the scope of Fram.

Fram's design is based around a few core interfaces, Fram ships with a few implementations of these interfaces, but it is  up to the user of Fram to customize it based on project needs. Each core interface is designed to be as small as possible making the implementation trivial, thus extending Fram for projects needs should be possible within minutes.

![](https://github.com/paketphp/fram/workflows/tests/badge.svg)

## Installation

`composer require paket/fram`

## Usage

```
$router = new SimpleRouter(
    ['GET' => [
        '/' => IndexView::class
    ]]);
$fram = new Fram(new DefaultViewFactory(), $router, new HtmlViewHandler());

$fram->run(function (Route $route, ?Throwable $throwable) {
    if (isset($throwable)) {
        return $route->withViewClass(View500::class, $throwable);
    }

    if ($route->hasEmptyView()) {
        return $route->withViewClass(View404::class);
    }
    return $route;
});
```

### Examples

See `/examples`

#### Running examples

`php -S localhost:8888 -t examples/www`

## Core parts

### View

In Fram a `View` acts as both the controller and the view. To create a view you implement a specific `View` interface, thus one class is only one `View`. Grouping similar views should be done using namespaces. 

View interfaces included in the framework

* `HtmlView`
* `JsonView`
* `SimpleView`

All view types implements an empty View interface.

By using different view types the framework can adapt its execution per view type. Any number of `View` interfaces can be created by the project for different scenarios.

How the `View` is rendered, the method name and it's parameters, is decided per view type, thus different view types can provide different input, e.g. like request and response objects.

#### EmptyView

`EmptyView` is a special view that represents the `null` `View`, i.e.
when the is no matching View for the current request. Every request begins with `EmptyView` set.

### ViewFactory

To instantiate a `View` class Fram uses a `ViewFactory`

View factories included in the framework

* `DefaultViewFactory` - instantiates the View without any constructor arguments
* `BeroViewFactory` - uses [Bero](https://github.com/paketphp/bero) dependency injector to instantiate Views

### ViewHandler

View handlers executes view types. Typical things that happens in a view handler is setting headers, specialized logging, handling buffers or executing a middleware pipe. It is the `ViewHandler` responsibility to provide the `View` with the input enforced by the view type.

View handlers included in the framework

* `HtmlViewHandler`
* `JsonViewHandler`
* `SimpleViewHandler`

### Route

`Route` is passed thru each part of Fram. `Route` knows the HTTP method, URI and what is the current `View`. A `Route` is immutable and the only way to change the current `View` is by cloning `Route`, this is done by either calling `withView()` or `withViewClass()`. By changing `View` by creating a new `Route` and pass the  new `Route` back upstreams achieves internal redirects within Fram. Fram will redirect until the `Route` is not changed.

### Router

`Router` matches a `Route` with a `View`.

Routers included in the framework

* `SimpleRouter` - simple string matching against uri
* `FastRouteRouter` - uses [FastRoute](https://github.com/nikic/FastRoute) for routing
* `MultiRouter` - uses multiple routers for routing

`Router` uses internal redirect by changing `View`.

### Fram

Fram is the engine that basically pumps the `Route` thru the system.

By calling `run()` on Fram the framework initiates a request.
`run()` is called by providing a callback, this callback gives the project the necessary control over each Route change and can either approve or change `Route`. 

The `run()` callback is  powerful mechanism the enables Fram to integrate better with existing code or other frameworks. By having the possibility to inspect each `Route` change, the callback can redirect or abort if needed. Typical scenarios are debugging, different execution path depending on environment, fallback to legacy code and authorization checks.

It is thru the `run()` callback how 404 pages are managed. Fram has no knowledge about how to handle the `EmptyView` case, but the `run()` callback can instruct Fram how to handle it by doing an internal redirect to the correct `View`.

Fram also catches exceptions that can happen either in `Router` or in the `ViewHandler` and  the `View`. Exceptions are passed to the `run()` callback as an optional `Throwable` second parameter, callback can then redirect with a new `Route` if needed. Fram does not catch exceptions happening in the `run()` callback.

If the `run()` callback returns `null` the execution of Fram stops.

#### Flow of Fram

1. initiates a `Route`
2. executes the `Router` to set a `View` on `Route`
3. calls `run()` callback with `Route` and optional `Throwable` if an exception happened
4. `run()` callback returns the same `Route`, a new `Route` or `null` to cancel
5. matches the `Route`'s `View` with registered view handlers
6. executes matched `ViewHandler` with `Route`
7. calls 3 if returning `Route` from `ViewHandler` has changed or on exception

## Error handling

Fram does not register any exception, error or shutdown handlers. That is something that needs to be configured outside of the framework.
