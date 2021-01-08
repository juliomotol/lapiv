<?php

namespace JulioMotol\Lapiv;

use Illuminate\Contracts\Container\Container;
use JulioMotol\Lapiv\Concerns\DispatchesVersionedApi;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\Controller;

class LumenGatewayController extends Controller
{
    use DispatchesVersionedApi;

    /**
     * Create an ApiController Instance.
     */
    public function __construct(Request $request, Container $container)
    {
        $this->request = $request;
        $this->container = $container;
    }

    protected function callApiVersionAction($controller, $method)
    {
        return $this->container->call([$controller, $method]);
    }
}
