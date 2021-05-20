<?php

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

];
