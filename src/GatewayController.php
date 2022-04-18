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
    protected array $apiControllers = [];

    public function __construct(
        protected Request $request,
        protected ControllerDispatcher $controllerDispatcher,
        protected Container $container
    ) {
    }

    public function __invoke(): mixed
    {
        return $this->dispatchApiAction('__invoke');
    }

    public function __call($method, $parameters)
    {
        return $this->dispatchApiAction($method);
    }

    protected function dispatchApiAction(string $method): mixed
    {
        return $this->controllerDispatcher->dispatch(
            $this->request->route(),
            $this->getControllerByVersion($this->getVersion()),
            $method
        );
    }

    private function getVersion(): string|int
    {
        return tap(Lapiv::getVersion(), function ($version) {
            if (! is_numeric($version) || $version <= 0) {
                throw new InvalidArgumentException('API Version must be an integer and not <= 0');
            }
        });
    }

    private function getControllerByVersion(string|int $version): mixed
    {
        $controller = $this->apiControllers[$version - 1] ?? null;

        if (! $controller) {
            throw new ApiVersionNotFoundException();
        }

        return $this->container->make($controller);
    }
}
