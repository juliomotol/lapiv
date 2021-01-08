<?php

namespace JulioMotol\Lapiv\Concerns;

use JulioMotol\Lapiv\Exceptions\InvalidArgumentException;
use JulioMotol\Lapiv\Exceptions\NotFoundApiVersionException;

trait DispatchesVersionedApi
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var \Illuminate\Contracts\Container\Container */
    protected $container;
    
    /** @var array */
    protected $apiControllers = [];

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $version = $this->getVersion();
        $controller = $this->getControllerByVersion($version);

        return $this->callApiVersionAction($controller, $method);
    }

    abstract protected function callApiVersionAction($controller, $method);

    protected function getVersion()
    {
        $method = config('lapiv.default');
        $methodOptions = config('lapiv.methods.' . $method);

        $version = null;

        switch ($method) {
            case 'uri':
                $version = $this->request->route('version', null);

                break;
            case 'query_string':
                $version = $this->request[$methodOptions['key']] ?? null;

                break;
            default:
                throw new InvalidArgumentException('"' . $method . '" is not a valid versioning method.');
        }

        if (! is_numeric($version) || $version <= 0) {
            throw new InvalidArgumentException('API Version must be a valid number and not <= 0');
        }

        return $version;
    }

    protected function getControllerByVersion($version)
    {
        $controller = $this->apiControllers[$version - 1] ?? null;

        if (! $controller) {
            throw new NotFoundApiVersionException();
        }

        return $this->container->make($controller);
    }
}
