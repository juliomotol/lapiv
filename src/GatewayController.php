<?php

namespace JulioMotol\Lapiv;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ControllerDispatcher;

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

        $controller = $this->container->make($this->apiControllers[$version - 1]);

        return $this->controllerDispatcher->dispatch($this->request->route(), $controller, $method);
    }

    private function getVersion()
    {
        $method = config('lapiv.default');
        $methodOptions = config('lapiv.methods.'.$method);

        switch ($method) {
            case 'uri':
                return $this->request->route()->parameter('version', null);
            case 'query_string':
                return $this->request[$methodOptions['key']] ?? null;
            case 'header':
                $headerValue = $this->request->header($methodOptions['key']) ?? null;
                $matches = [];
                
                preg_match($methodOptions['pattern'], $headerValue, $matches);

                return $matches[1] ?? null;
        }
    }
}
