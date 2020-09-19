<?php

namespace JulioMotol\Lapiv\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use JulioMotol\Lapiv\LapivServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [LapivServiceProvider::class];
    }
}
