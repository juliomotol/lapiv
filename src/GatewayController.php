<?php

namespace JulioMotol\Lapiv;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ControllerDispatcher;
use JulioMotol\Lapiv\Concerns\DispatchesVersionedApi;

class GatewayController extends Controller
{
    use DispatchesVersionedApi;

    /** @var Illuminate\Routing\ControllerDispatcher */
    protected $controllerDispatcher;

    /**
     * Create an ApiController Instance.
     */
    public function __construct(Request $request, ControllerDispatcher $controllerDispatcher, Container $container)
    {
        $this->request = $request;
        $this->controllerDispatcher = $controllerDispatcher;
        $this->container = $container;
    }

    protected function callApiVersionAction($controller, $method)
    {
        return $this->controllerDispatcher->dispatch($this->request->route(), $controller, $method);
    }
}
