<?php

namespace JulioMotol\Lapiv\Tests;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use JulioMotol\Lapiv\Exceptions\ApiVersionNotFoundException;
use JulioMotol\Lapiv\Tests\Controllers\Api\FooV1Controller;

class GatewayControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => true]);
    }

    /** @test */
    public function it_can_handle_api_versioned_route_actions_with_uri_versioning_method()
    {
        $this->registerRoute();

        $response = $this->getJson('v1/foo');

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    /** @test */
    public function it_can_handle_api_versioned_route_actions_with_query_string_versioning_method()
    {
        config(['lapiv.default' => 'query_string']);

        $this->registerRoute();

        $response = $this->getJson('foo?v=1');

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    /** @test */
    public function it_throws_exception_when_invalid_version_given()
    {
        $this->registerRoute();

        $response = $this->getJson('vasdf/foo');

        $response->assertStatus(500);
        $this->assertEquals(InvalidArgumentException::class, $response->json('exception'));
    }

    /** @test */
    public function it_throws_exception_when_version_not_found()
    {
        $this->registerRoute();

        $response = $this->getJson('v2/foo');

        $response->assertStatus(404);
        $this->assertEquals(ApiVersionNotFoundException::class, $response->json('exception'));
    }

    /** @test */
    public function it_throws_exception_when_version_action_not_found()
    {
        $this->registerRoute(function () {
            Route::get('/foo/bar', 'FooGatewayController@bar');
        });

        $response = $this->getJson('v1/foo/bar');

        $response->assertStatus(500);
        $this->assertEquals(\BadMethodCallException::class, $response->json('exception'));
    }

    /** @test */
    public function it_can_handle_single_action_controller()
    {
        $this->registerRoute(function () {
            Route::get('foo-invoke', 'FooGatewayController');
        });

        $response = $this->getJson('v1/foo-invoke');

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    private function registerRoute(\Closure $closure = null)
    {
        Route::namespace('\JulioMotol\Lapiv\Tests\Controllers\Api')
            ->group(
                function () use ($closure) {
                    Route::lapiv(function () use ($closure) {
                        Route::get('foo', 'FooGatewayController@index');

                        if ($closure) {
                            call_user_func($closure);
                        }
                    });
                }
            );
    }
}
