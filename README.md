# Fram

Fram (_Swedish_ in front) is a view framework for PHP. Fram's goal is to fit everywhere, new projects, existing projects,
side by side with other frameworks, thus Fram is a flexible framework that can fit into many scenarios.

Fram's design is convention based instead of providing everything but the kitchen sink like other frameworks. It is  up to the user of Fram to customize it based on project needs.

## Installation

`composer require paket/fram`

## General

Each part of Fram is designed to be as simple as possible, it should be easy change or extend each part. 

### Router

Router matches HTTP method and URI with a Route, a Route has a View.

Routers included in the framework

* SimpleRouter - simple string matching against uri
* FastRouteRouter - uses FastRoute for routing
* MultiRouter - uses multiple Routers for routing

### ViewFactory

To instantiate a View class the Router uses a View factory

View factories included in the framework

* DefaultViewFactory - instantiates the View without any constructor arguments
* BeroViewFactory - uses [Bero](https://github.com/paketphp/bero) dependency injector to instantiate Views

### View

In Fram a view acts as both the controller and the view. It is possible to split these two into different layers by  customizing, but from Fram's perspective it only sees views. To create a view you implement a specific View interface, 
thus one class is only one View. Grouping similar views should be done using namespaces. 

View interfaces included in the framework

* HtmlView
* JsonView
* SimpleView

All view types implements an empty View interface.

By using different view types the framework can adapt its execution per view type. Any number of View interfaces can be created by the project for different scenarios.

How the View is rendered, the method name and it's parameters, is decided per view type, thus different view types can provide different input, e.g. like request and response objects.

### ViewHandler

View handlers executes View types. Typical things that happens in a View handler is setting headers, specialized logging, handling buffers or executing a middleware pipe. It is the View handler responsibility to provide the View with the input enforced by the view type.

View handlers included in the framework

* HtmlViewHandler
* JsonViewHandler
* SimpleViewHandler

### Fram

Fram glues all these parts together

* executes the Router to get a Route
* matches the Route's View with registered View handlers
* executes matched View handler with Route

## Error handling

Fram does not solve error handling. That is something that needs to be configured outside 
of the framework.

## Examples

See `/examples`

### Running examples

`php -S localhost:8888 -t examples/www`
