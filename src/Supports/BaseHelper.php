<?php

namespace Weoneteam\PluginManager\Supports;

use Weoneteam\PluginManager\Supports\BaseHelperSupport;
use Illuminate\Support\Facades\Facade;

class BaseHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseHelperSupport::class;
    }
}
