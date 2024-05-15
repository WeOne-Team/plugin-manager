<?php
use Illuminate\Support\Facades\File;
use Weoneteam\PluginManager\Supports\Setting;
use Weoneteam\PluginManager\Supports\BaseHelper;

if (! function_exists('plugin_path')) {
    function plugin_path(?string $path = null): string
    {
        return platform_path('modules' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
    }
}

if (! function_exists('platform_path')) {
    function platform_path(?string $path = null): string
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);

        return base_path(($path ? DIRECTORY_SEPARATOR . $path : ''));
    }
}

if (! function_exists('is_plugin_active')) {
    function is_plugin_active(string $alias): bool
    {
        return in_array($alias, get_active_plugins());
    }
}


if (! function_exists('get_active_plugins')) {
    function get_active_plugins(): array
    {
        $activatedPlugins = '';
        //$activatedPlugins = Setting::get('activated_plugins');

        if (! $activatedPlugins) {
            return [];
        }

        $activatedPlugins = json_decode($activatedPlugins, true);

        if (! $activatedPlugins) {
            return [];
        }

        $plugins = array_unique($activatedPlugins);

        $existingPlugins = BaseHelper::scanFolder(plugin_path());

        $activatedPlugins = array_diff($plugins, array_diff($plugins, $existingPlugins));

        return array_values($activatedPlugins);
    }
}
