<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Manager;
use JulioMotol\Lapiv\Drivers\BaseDriver;
use JulioMotol\Lapiv\Drivers\QueryStringDriver;
use JulioMotol\Lapiv\Drivers\UriDriver;

class ApiVersioningManager extends Manager
{
    public function getDefaultDriver()
    {
        return $this->config->get('lapiv.default');
    }

    public function getDefaultDriverOptions()
    {
        return $this->config->get('lapiv.methods.' . $this->getDefaultDriver());
    }

    public function createUriDriver()
    {
        return new UriDriver($this->getDefaultDriverOptions());
    }

    public function createQueryStringDriver()
    {
        return new QueryStringDriver($this->getDefaultDriverOptions());
    }
}
