<?php

namespace Juliomotol\Lapiv\Tests;

use Orchestra\Testbench\TestCase;
use Juliomotol\Lapiv\LapivServiceProvider;

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
