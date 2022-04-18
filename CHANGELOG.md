# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added

- `ApiVersioningManager` and `Base Drivers` for handling routing and version checking.
- Support for Laravel 9.x

### Changed

- Minimum requirement to PHP8

### Removed

- Removed `Route::lapiv()` chaining. See [issue](https://github.com/juliomotol/lapiv/issues/1).

## v2.0.0 (2021-01-09)

### Added

-   PHP 8 Support.
-   Single action controllers via `__invoke`.

### Removed

-   Drop Laravel >= 7 support.
-   Remove `header` versioning method.
-   Remove `base_namespace` and `base_prefix` config.

    -   `Route::lapiv()` received an overhaul and it has been decided that these configurations shouldn't be handled by `Route::lapiv()`
    -   If you want to retain the old behaviour, you'll need to modify `Route::lapiv()` like so:

        ```php
        Route::namespace('\App\Http\Controllers\Api')
            ->group(function () {
                Route::lapiv(function () {
                    Route::get('foo', 'FooGatewayController@index');
                    Route::get('foo/{foo}', 'FooGatewayController@show');
                })
            });
        ```

## v1.0.1 (2021-01-08)

### Changed

-   Fix version validation.

## 1.0.0 - 2020-09-19

-   🎉Initial Release!
