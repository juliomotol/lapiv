<?php

namespace Juliomotol\Lapiv;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Juliomotol\Lapiv\Skeleton\SkeletonClass
 */
class LapivFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lapiv';
    }
}
