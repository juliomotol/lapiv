# Lapiv

![GitHub Workflow Status (event)](https://img.shields.io/github/workflow/status/juliomotol/lapiv/Run%20Laravel%206.x%20tests?event=push&label=Laravel%206%20Build&style=flat-square)
![GitHub Workflow Status (event)](https://img.shields.io/github/workflow/status/juliomotol/lapiv/Run%20Laravel%207.x%20tests?event=push&label=Laravel%207%20Build&style=flat-square)
![GitHub Workflow Status (event)](https://img.shields.io/github/workflow/status/juliomotol/lapiv/Run%20Laravel%208.x%20tests?event=push&label=Laravel%208%20Build&style=flat-square)
[![StyleCI](https://github.styleci.io/repos/295691801/shield?branch=master)](https://github.styleci.io/repos/295691801?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/juliomotol/lapiv.svg?style=flat-square)](https://packagist.org/packages/juliomotol/lapiv)
[![Total Downloads](https://img.shields.io/packagist/dt/juliomotol/lapiv.svg?style=flat-square)](https://packagist.org/packages/juliomotol/lapiv)

A Small Laravel 6+ package for a simple and easy API versioning.

> Lapiv simply stands for (L)aravel (API) (V)ersioning.

## Table of Contents

-   [Installation](#installation)
-   [Config](#config)
-   [Setup](#setup)
    -   [Controller](#foov1controller.php)
    -   [GatewayController](#foogatewaycontroller.php)
    -   [Routing](#routing)
    -   [Bumping a version](#bumping-a-version)
-   [Versioning Methods](#versioning-methods)
    -   [`uri` Method](#uri-method)
    -   [`query_string` Method](#query_string-method)

## Installation

You can install the package via composer:

```bash
composer require juliomotol/lapiv
```

## Config

| Key                      | Default Value  | Description                                                                                       |
| ------------------------ | -------------- | ------------------------------------------------------------------------------------------------- |
| default                  | `"uri"`        | The versioning method. Supports: "uri", "query_string".                                           |
| methods.uri.prefix       | `"v{version}"` | The prefix for uri based versioning. (NOTE: Always include the "version" parameter in the prefix) |
| methods.query_string.key | `"v"`          | The query string key name for determining the version                                             |

If you want to make changes in the configuration you can publish the config file using:

```
php artisan vendor:publish --provider="JulioMotol\Lapiv\LapivServiceProvider"
```

## Setup

Assuming you will be using the default configuration, we suggest you to follow the following directory structure.

```
+-- app
    +-- Http
        +-- Controllers
            +-- Api
                +-- FooGatewayController.php
                +-- FooV1Controller.php
```

### `FooV1Controller.php`

This is very much like your standard controller. Nothing special here really. All action methods must be declared here e.g. `index`, `create`, `show`, etc.

```php
namespace App\Http\Controllers\Api\Foo;

use App\Http\Controllers\Controller;

class FooV1Controller extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'This is Foo V1']);
    }
}
```

### `FooGatewayController.php`

Now the good stuff. This controller **MUST** extend `\JulioMotol\Lapiv\GatewayController` in order for this whole thing to work. This will be in charge of dispatching the requests based on the requested version. Let's take a look inside.

```php
namespace App\Http\Controllers\Api\Foo;

use JulioMotol\Lapiv\GatewayController;

class FooGatewayController extends GatewayController
{
    protected $apiControllers = [
        FooV1Controller::class, // The first version of you API endpoint.
        // Preceeding version implementations...
    ];
}
```

> The order in `$apiControllers` is critical. The first controller declared will be our `v1`, then will be `v2`, and so on.

### Routing

With our controllers ready to go, lets create our route. Go to `routes/api.php`

```php
/**
 * Registers a versioned API endpoint.
 *
 * Router::lapiv($callback = null)
 *
 * @param $callback
 */
Route::lapiv(function () {
    Route::get('/foo', 'FooGatewayController@index');
    Route::get('/bar', 'BarGatewayController@index');
});

// Or if you fancy chaining...

Route::lapiv()->get('/foo', 'FooGatewayController@index');
Route::lapiv()->get('/bar', 'BarGatewayController@index');
```

Notice we didn't point to the `FooV1Controller@index`. As we've said, the `FooGatewayController` will be doing much of the heavy work, so we'll just call that instead.

When you run `php artisan route:list` you should see this.

| Method    | URI                | Action                                              |
| --------- | ------------------ | --------------------------------------------------- |
| GET\|HEAD | api/v{version}/foo | App\Http\Controllers\Api\FooGatewayController@index |
| GET\|HEAD | api/v{version}/bar | App\Http\Controllers\Api\BarGatewayController@index |

Now, when we try to go to `/api/v1/foo`, it should be handled by `FooV1Controller`.

### Bumping a version

When your ready to bump your API version to v2, Simply add a new `FooV2Controller` and dont forget to add that to `FooGatewayController`'s `$apiControllers`.

## Versioning Methods

This package supports 2 types of API Versioning methods, `uri` and `query_string`.

### `uri` Method

This is the default of the versioning method. Here, the API version will be declared in the uri path (e.g. `example.com/api/v1/foo`).

In the config, you can change the prefix for the uri.

```php
"methods" => [
    "uri" => [
        "prefix" => '/version-{version}' // will generate `example.com/api/version-1/foo`
    ]
]
```

> Don't forget to add the `version` parameter in the prefix.

### `query_string` Method

Here, the API version will be declared in the query string (e.g. `example.com/api/foo?v=1`).

In the config, you can change the query string key.

```php
"methods" => [
    "query_string" => [
        "key" => 'version' // will accept `example.com/api/foo?version=1`
    ]
]
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email julio.motol89@gmail.com instead of using the issue tracker.

## Credits

-   [Julio Motol](https://github.com/juliomotol)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
