<?php

namespace Weoneteam\PluginManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Weoneteam\PluginManager\Supports\BaseHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class PluginManagerController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        $plugins = collect();
        if (! empty($pluginsPath = BaseHelper::scanFolder(plugin_path()))) {
            $installed = get_active_plugins();

            foreach ($pluginsPath as $path) {
                $pluginPath = plugin_path($path);

                if (File::exists($dsStore = "$pluginPath/.DS_Store")) {
                    File::delete($dsStore);
                }

                if (
                    ! File::isDirectory($pluginPath)
                    || ! File::exists($pluginJson = "$pluginPath/plugin.json")
                ) {
                    continue;
                }

                $manifest = BaseHelper::getFileData($pluginJson);

                if (! empty($manifest)) {
                    $manifest = [
                        ...$manifest,
                        'status' => in_array($path, $installed),
                        'path' => $path,
                        'image' => null,
                    ];

                    $screenshot = "https://opengraph.githubassets.com/3e5790fa72ce7003d834c2ae6eb870977ec613b95992436f074ea75ace8f1837/".$manifest['id'];
                    $manifest['image'] = $screenshot;
                    if (File::exists($pluginPath . '/screenshot.png')) {
                        $manifest['image'] = 'data:image/png;base64,' . base64_encode(File::get($pluginPath . '/screenshot.png'));
                    }

                    $plugins->push((object) $manifest);
                }
            }

            $plugins = collect($plugins)->sortByDesc('status');
        }
        return view('plugin-manager::index', compact('plugins'));
    }
}
