<?php

use Illuminate\Support\Str;

$appSlug = Str::slug(env('APP_NAME', 'laravel'), '_');

return [

    /*
    |--------------------------------------------------------------------------
    | The versioning method.
    |--------------------------------------------------------------------------
    |
    | This option controls the default versioning method for your API routes.
    |
    | Supported: "uri", "query_string"
    |
    */

    'default' => 'uri',

    /*
    |--------------------------------------------------------------------------
    | Versioning Method Configurations.
    |--------------------------------------------------------------------------
    |
    | The configurations for versioning methods.
    |
    */

    'methods' => [
        'uri' => [
            'prefix' => 'v{version}',
        ],
        'query_string' => [
            'key' => 'v',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Namespace
    |--------------------------------------------------------------------------
    |
    | The base namespace of the versioned API Controllers.
    |
    */

    'base_namespace' => '\App\Http\Controllers\Api',

    /*
    |--------------------------------------------------------------------------
    | Base Route
    |--------------------------------------------------------------------------
    |
    | The base route prefix.
    |
    */

    'base_route' => '',

];
