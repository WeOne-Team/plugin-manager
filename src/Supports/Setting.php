<?php
namespace Weoneteam\PluginManager\Supports;
use Weoneteam\PluginManager\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingStore::class;
    }
}
