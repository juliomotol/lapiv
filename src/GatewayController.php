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
    /** @var Request */
    protected $request;

    /** @var ControllerDispatcher */
    protected $controllerDispatcher;

    /** @var Container */
    protected $container;

    /** @var array */
    protected $apiControllers = [];

    /**
     * Create an ApiController Instance.
     *
     * @param Request $request
     * @param ControllerDispatcher $controllerDispatcher
     * @param Container $container
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
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->controllerDispatcher->dispatch(
            $this->request->route(),
            $this->getControllerByVersion($this->getVersion()),
            $method
        );
    }

    /**
     * @return string|int $version
     */
    private function getVersion()
    {
        $method = config('lapiv.default');
        $methodOptions = config('lapiv.methods.'.$method);

        $version = null;

        switch ($method) {
            case 'uri':
                $version = $this->request->route('version', null);

                break;
            case 'query_string':
                $version = $this->request->input($methodOptions['key']) ?? null;

                break;
            default:
                throw new InvalidArgumentException('"'.$method.'" is not a valid versioning method.');
        }

        if (! is_numeric($version) || $version <= 0) {
            throw new InvalidArgumentException('API Version must be a valid number and not <= 0');
        }

        return $version;
    }

    /**
     * @param string|int $version
     *
     * @return Controller
     */
    private function getControllerByVersion($version)
    {
        $controller = $this->apiControllers[$version - 1] ?? null;

        if (! $controller) {
            throw new NotFoundApiVersionException();
        }

        return $this->container->make($controller);
    }
}
