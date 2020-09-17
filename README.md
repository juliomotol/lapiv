# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juliomotol/lapiv.svg?style=flat-square)](https://packagist.org/packages/juliomotol/lapiv)
[![Total Downloads](https://img.shields.io/packagist/dt/juliomotol/lapiv.svg?style=flat-square)](https://packagist.org/packages/juliomotol/lapiv)

A Small Laravel 6+ package for a simple and easy API versioning that aims to remove the overhead of writing invidual routes per version implementation.

> Lapiv simply stands for (L)aravel (API) (V)ersioning.

## Installation

You can install the package via composer:

```bash
composer require juliomotol/lapiv
```

## Config

| Key                       | Default Value                                    | Description                                                                                                                                                     |
| ------------------------- | ------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| "default"                 | "uri"                                            | The versioning method. Supports: "uri", "query_string", "header"                                                                                                |
| "method.uri.prefix"       | "/v{version}"                                    | The prefix for uri based versioning. (NOTE: Always include the "version" parameter in the prefix)                                                               |
| "method.query_string.key" | "v"                                              | The query string key name for determining the version                                                                                                           |
| "method.header.key"       | "Accept"                                         | The header key name for determining the version                                                                                                                 |
| "method.header.pattern"   | "/application\/vnd\.\${appSlug}\.v(\d\*)\+json/" | The pattern for determining the version based on the given header value. See [Header value pattern](#header-value-pattern)                                      |
| "base_namespace"          | "\App\Http\Controllers\Api"                      | The base namespace for your versioned API controllers                                                                                                           |
| "base_route"              | ""                                               | The base route prefix. (This is omitted by default since we expect you to place your api routes inside `routes/api.php`. Other wise, feel free to modify this.) |

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
                +-- Foo
                    +-- FooGatewayController.php
                    +-- FooV1Controller.php
```

### `FooV1Controller.php`

This is very much like your standard controller. Nothing special here really.

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

> The order in `$apiControllers` is critical. The first controller declared will be our `v1`, second will be `v2`, and so on.

### Routing

With our controllers ready to go, lets create our route. Go to `routes/api.php`

```php
Route::lapiv('foo', 'Foo', function () {
    Route::get('/', 'FooGatewayController@index');
});
```

Notice we didn't point to the `FooV1Controller@index`. As we've said, the `FooGatewayController` will be doing much of the heavy work, so we'll just call that instead.

When you run `php artisan route:list` you should see this.

| Method    | URI                | Action                                                  |
| --------- | ------------------ | ------------------------------------------------------- |
| GET\|HEAD | api/v{version}/foo | App\Http\Controllers\Api\Foo\FooGatewayController@index |

Now, when we try to go to `/api/v1/foo`, it should be handled by `FooV1Controller`.

## Versioning Methods

This package supports 3 types of API Versioning methods namely `uri`, `query_string`, and `header`.

### `uri` Method

This is the default of the versioning method. Here, the API version should be declared in the uri path (e.g. `example.com/api/v1/foo`).

In the config, you can change the prefix for the uri.

```php
"methods" => [
    "uri" => [
        "prefix" => '/version-{version}' // will generate `example.com/api/version-1/foo`
    ]
]
```

> Don't forget to add the `version` parameter for the prefix.

### `query_string` Method

Here, the API version should be declared in the query string (e.g. `example.com/api/foo?v=1`).

In the config, you can change the query string key.

```php
"methods" => [
    "query_string" => [
        "key" => 'version' // will generate `example.com/api/foo?version=1`
    ]
]
```

### `header` Method

Here the API version should be declared in the headers. By the default it will be checking the `Accept` header with a pattern of `/application\/vnd\.\${appSlug}\.v(\d\*)\+json/` where the \$appSlug is a snake case of your `APP_NAME`.

```php
"methods" => [
    "header" => [
        "key" => 'Accept-version',
        "pattern" => null // giving `null` will assume that you are expecting a number.
    ]
]
```

This will now accept a `Accept-version=1` header.

> Applying a different pattern, dont forget to capture the version number with `(\d)+`

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
