<?php

namespace JulioMotol\Lapiv\Tests;

use JulioMotol\Lapiv\LapivServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [LapivServiceProvider::class];
    }
}
