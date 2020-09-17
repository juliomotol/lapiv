<?php

namespace JulioMotol\Lapiv\Tests;

use Orchestra\Testbench\TestCase;
use JulioMotol\Lapiv\LapivServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LapivServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
