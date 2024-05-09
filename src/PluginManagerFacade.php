<?php

namespace Weoneteam\PluginManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Weoneteam\PluginManager\Skeleton\SkeletonClass
 */
class PluginManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'plugin-manager';
    }
}
