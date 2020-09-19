<?php

namespace JulioMotol\Lapiv;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ControllerDispatcher;
use JulioMotol\Lapiv\Exceptions\InvalidArgumentException;
use JulioMotol\Lapiv\Exceptions\NotFoundApiVersionException;

class GatewayController extends Controller
{
    /** @var Illuminate\Http\Request */
    protected $request;

    /** @var Illuminate\Routing\ControllerDispatcher */
    protected $controllerDispatcher;

    /** @var  Illuminate\Contracts\Container\Container */
    protected $container;

    /** @var array */
    protected $apiControllers = [];

    /**
     * Create an ApiController Instance.
     */
    public function __construct(Request $request, ControllerDispatcher $controllerDispatcher, Container $container)
    {
        $this->request = $request;
        $this->controllerDispatcher = $controllerDispatcher;
        $this->container = $container;
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $version = $this->getVersion();
        $controllerClass = $this->getControllerClassByVersion($version);
        $controller = $this->container->make($controllerClass);

        return $this->controllerDispatcher->dispatch($this->request->route(), $controller, $method);
    }

    private function getVersion()
    {
        $method = config('lapiv.default');
        $methodOptions = config('lapiv.methods.' . $method);

        $version = null;

        switch ($method) {
            case 'uri':
                $version = $this->request->route()->parameter('version', null);
                break;
            case 'query_string':
                $version = $this->request[$methodOptions['key']] ?? null;
                break;
            case 'header':
                $version = $headerValue = $this->request->header($methodOptions['key']) ?? null;

                if ($methodOptions['pattern']) {
                    $matches = [];

                    preg_match($methodOptions['pattern'], $headerValue, $matches);

                    $version = $matches[1] ?? null;
                }
                break;
            default:
                throw new InvalidArgumentException('"'.$method.'" is not a valid versioning method.');
        }

        if (!is_numeric($version) && $version <= 0) {
            throw new InvalidArgumentException('API Version must be a valid number and not <= 0');
        }

        return $version;
    }

    private function getControllerClassByVersion($version)
    {
        $controller = $this->apiControllers[$version - 1] ?? null;

        if (!$controller) {
            throw new NotFoundApiVersionException();
        }

        return $controller;
    }
}
