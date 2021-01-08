<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Facades\Route;
use JulioMotol\Lapiv\Exceptions\InvalidArgumentException;

class LapivRoute
{
    public function __invoke($callback = null)
    {
        switch ($method = config('lapiv.default')) {
            case 'uri':
                return $this->handleApiVersioning($callback);
            case 'query_string':
                return $this->handleQueryStringVersioning($callback);
            default:
                throw new InvalidArgumentException('"' . $method . '" is not a valid versioning method.');
        }
    }

    protected function handleApiVersioning($callback = null)
    {
        return $callback
            ? Route::group(['prefix' => config('lapiv.methods.uri.prefix')], $callback)
            : Route::prefix(config('lapiv.methods.uri.prefix'));
    }

    protected function handleQueryStringVersioning($callback = null)
    {
        return $callback
            ? Route::group([], $callback)
            : Route::prefix('/');
    }
}
