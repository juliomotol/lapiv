<?php

namespace JulioMotol\Lapiv;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ControllerDispatcher;
use InvalidArgumentException;
use JulioMotol\Lapiv\Exceptions\ApiVersionNotFoundException;

class GatewayController extends Controller
{
    protected Request $request;

    protected ControllerDispatcher $controllerDispatcher;

    protected Container $container;

    protected array $apiControllers = [];

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
     * @return mixed
     */
    public function __invoke()
    {
        return $this->dispatchApiAction('__invoke');
    }

    /**
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->dispatchApiAction($method);
    }

    /**
     * @param  string  $method
     *
     * @return mixed
     */
    protected function dispatchApiAction($method)
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
        return tap(Lapiv::getVersion(), function ($version) {
            if (!is_numeric($version) || $version <= 0) {
                throw new InvalidArgumentException('API Version must be an integer and not <= 0');
            }
        });
    }

    /**
     * @param string|int $version
     *
     * @return Controller
     */
    private function getControllerByVersion($version)
    {
        $controller = $this->apiControllers[$version - 1] ?? null;

        if (!$controller) {
            throw new ApiVersionNotFoundException();
        }

        return $this->container->make($controller);
    }
}
