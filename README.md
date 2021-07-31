# Fram

Fram (_Swedish_ in front) is a view framework for PHP. Fram's goal is to fit everywhere, new projects, existing projects,
side by side with other frameworks, thus Fram is a flexible framework that can fit into many scenarios.

Fram is a view framework only, based on a route a view can be rendered. Other things necessary for a project like database handling, authentication, template rendering or logging, is not within the scope of Fram.

Fram's design is based around a few core interfaces, Fram ships with a few implementations of these interfaces, but it is  up to the user of Fram to customize it based on project needs. Each core interface is designed to be as small as possible making the implementation trivial, thus extending Fram for projects needs should be possible within minutes.

![](https://github.com/paketphp/fram/workflows/tests/badge.svg)

## Installation

`composer require paket/fram`

Requires PHP 7.2 or higher.

## Usage

```
$container = new BeroContainer(new StrictBero); // any PSR-11 ContainerInterface
$router = new SimpleRouter(
    ['GET' => [
        '/' => IndexView::class
    ]]);
$fram = new Fram($container, $router, new HtmlViewHandler());

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

**Note**: examples should be used as examples only and not as library code.

#### Running examples

`php -S localhost:8888 -t examples/www`

## Core parts

### View

In Fram a `View` acts as both the controller and the view. To create a view you implement a specific `View` interface, thus one class is only one `View`. Grouping similar views should be done using namespaces. 

View interfaces included in the framework

* `DefaultView`
* `HtmlView`
* `JsonView`

All view types implements an empty `View` interface.

By using different view types the framework can adapt its execution per view type. Any number of `View` interfaces can be created by the project for different scenarios.

How the `View` is rendered, the method name and it's parameters, is decided per view type, thus different view types can provide different input, e.g. like request and response objects.

#### EmptyView

`EmptyView` is a special view that represents the `null` `View`, i.e.
when the is no matching View for the current request.

### ViewHandler

View handlers executes view types. Typical things that happens in a view handler is setting headers, specialized logging, handling buffers or executing a middleware pipe. It is the `ViewHandler` responsibility to provide the `View` with the input enforced by the view type. By switching to another `ViewHandler` you can change the behaviour of a `View`.

View handlers included in the framework

* `DefaultViewHandler`
* `HtmlViewHandler`
* `JsonViewHandler`


### Route

`Route` is passed thru each part of Fram. `Route` knows the HTTP method, URI and what is the current `View` class. A `Route` is immutable and the only way to change the current `View` class is by cloning `Route`, this is done by calling `withViewClass()`. By changing `View` class a new `Route` is returned and can then be passed back upstreams to achieve internal redirects within Fram. Fram will redirect until the `Route` is not changed. Note that `Route` keeps track of the current `View` class name, not the class instance itself.

### Router

`Router` matches HTTP method and HTTP uri with a `View` class and returns a `Route` with that `View` class set.

Routers included in the framework

* `SimpleRouter` - simple string matching against method and uri
* `FastRouter` - uses [FastRoute](https://github.com/nikic/FastRoute) for routing

### Container

To instantiate a classes Fram uses [PSR-11](https://www.php-fig.org/psr/psr-11/) `ContainerInterface`.

Suggested implementation and used in all documentation and examples is `BeroContainer`, `BeroContainer` is part of [Bero](https://github.com/paketphp/bero) project.

### Fram

Fram is the engine that basically pumps the `Route` thru the framework.

By calling `run()` on Fram the framework initiates a request.
`run()` is called by providing a callback, this callback gives the project the necessary control over each `Route` change and can either approve or change `Route`.

The `run()` callback is  powerful mechanism the enables Fram to integrate better with existing code or other frameworks. By having the possibility to inspect each `Route` change, the callback can redirect or abort if needed. Typical scenarios are debugging, different execution path depending on environment, fallback to legacy code and authorization checks.

It is thru the `run()` callback how 404 pages are managed. Fram has no knowledge about how to handle the `EmptyView` case, but the `run()` callback can instruct Fram how to handle it by doing an internal redirect to the correct `View`.

Fram also catches exceptions that can happen either in `Router` or in the `ViewHandler` and thus the `View`. Exceptions are passed to the `run()` callback as an optional `Throwable` second parameter, callback can then redirect with a new `Route` if needed. Fram does not catch exceptions happening in the `run()` callback, those exceptions should be caught outside of `run()`. `LogicExcpeption` will always stop Fram.

If the `run()` callback returns `null` the execution of Fram stops.

#### Flow of Fram

1. executes the `Router` with method and uri
2. `Router` returns a new `Route` with a `View` class set
3. calls `run()` callback with `Route` and optional `Throwable` if an exception happened
4. `run()` callback returns the same `Route`, a new `Route` or `null` to cancel
5. matches the `Route`'s `View` class with registered view handlers
6. instantiates `View` from `View` class name by using `ContainerInterface`
7. executes matched `ViewHandler` with `Route` and `View`
8. calls 3 if returning `Route` from `ViewHandler` has changed or on exception

## Error handling

Fram does not register any exception, error or shutdown handlers. That is something that needs to be configured outside of Fram.

## Customization

Most customization should be done by implementing your own `ViewHandler`. The `ViewHandler` is a good place for doing user authentication/authorization, buffered output, response header management, custom logging, [PSR-7](https://www.php-fig.org/psr/psr-7/) request & response objects or calling [PSR-15](https://www.php-fig.org/psr/psr-15/) middlewares.

## FAQ

* Should my view class directly implement the `View` interface?
  * No, the top `View` interface is an abstract interface and should never be implemented, the `View` interface is used as type to represent all view interfaces. You should implement a view interface that in turn extends the top `View` interface.
* Can a view interface extend another view interface?
  * Only if the other interface is an abstract interface like the top `View` interface. Assume we have view interface `A` that extends the `View` interface, then `B` interface extends `A` interface. Both `A` and `B` has their own view handlers so view implementations of `A` and `B` can be rendered, however a view class of `B` can then match both the view handler that is registered for `A` and `B`, this can lead to unexpected results and Fram does not handle this case.
