<?php

namespace Weoneteam\PluginManager\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Weoneteam\PluginManager\Supports\BaseHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use Macellan\Zip\Zip;
use Illuminate\Support\Facades\Storage;

class PluginManagerController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'app.menu.accountSettings';
        $this->activeSettingMenu = 'company_settings';
        $this->middleware(function ($request, $next) {
            return user()->permission('manage_company_setting') !== 'all' ? redirect()->route('profile-settings.index') : $next($request);
        });

    }
    public function index(Request $request)
    {
        $moduleList = Module::allEnabled();
        $activatedModule = [];
        foreach ($moduleList as $module)
        {
            $activatedModule[] = $module->getName();
        }
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
                    || ! File::exists($pluginJson = "$pluginPath/module.json")
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
                    $id = $manifest['id'] ?? "default";
                    $screenshot = "https://opengraph.githubassets.com/3e5790fa72ce7003d834c2ae6eb870977ec613b95992436f074ea75ace8f1837/".$id;
                    $manifest['image'] = $screenshot;
                    if (File::exists($pluginPath . '/screenshot.png')) {
                        $manifest['image'] = 'data:image/png;base64,' . base64_encode(File::get($pluginPath . '/screenshot.png'));
                    }

                    $plugins->push((object) $manifest);
                }
            }

            $plugins = collect($plugins)->sortByDesc('status');
        }
        $this->data['plugins'] = $plugins;
        $this->data['activatedModules'] = $activatedModule;

        return view('plugin-manager::index')->with($this->data);
    }
    public function active(Request $request)
    {
        $data = $request->all();
        $moduleName = $data["id"];
        $status = 'active';
        try
        {
            $targetModule = Module::findOrFail($moduleName);
            if($targetModule->isEnabled() == false)
            {
                $targetModule->register();
                $command = strtolower($moduleName) . ':enable';
                if (array_has(\Artisan::all(), $command) && ($status == 'active')) {
                    Artisan::call($command);
                }
                Module::enable($moduleName);

                Artisan::call('vendor:publish --tag="config|lang"');
                Artisan::call('optimize:clear');
                $targetModule->boot();
                return response()->json(['status' => 200, 'message' => 'Module is enabled'],200);
            }
            else
            {
                return response()->json(['status' => 403, 'message' => 'Module is already enabled'],403);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['status' => 403, 'message' => 'Module can not enable'],403);
        }


    }
    public function deactivate(Request $request)
    {
        $data = $request->all();
        $moduleName = $data["id"];
        $status = 'deactivate';
        try
        {
            $targetModule = Module::findOrFail($moduleName);
            if($targetModule->isEnabled() == true)
            {
                $targetModule->register();
                $command = 'module:disable '.$moduleName;
                if (array_has(\Artisan::all(), $command) && ($status == 'deactivate')) {
                    Artisan::call($command);
                }
                Module::disable($moduleName);
                echo Artisan::output();
                Artisan::call('optimize:clear');
            }
            else
            {

            }
        }
        catch (\Exception $e)
        {

        }
    }
    private function flushData()
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
    }
    public function upload(Request $request)
    {
        if (!extension_loaded('zip')) {
            return response()->json(['status' => 500, 'message' => 'Please enable ZIP extensions to install module'],500);
        }
        $file = $request->file('moduleFile');
        $fileName = $file->getClientOriginalName();
        $path = storage_path('app/uploads/modules/' . $fileName);

        $file->move('app/uploads/modules', $fileName);

        storage_path('uploads/modules/' . $fileName);
        $filePath = 'app/uploads/modules/' . $fileName;
        $zip = Zip::open($filePath);
        $zipName = $this->getZipName($filePath);
        $zip->extract(storage_path('app') . '/Modules');
        $moduleName = str_replace('.zip', '', $zipName);
        $validateModule = $this->validateModule($moduleName);
        if($validateModule === true)
        {
            File::moveDirectory(storage_path('app') . '/Modules/' . $moduleName, base_path() . '/Modules/' . $moduleName, true);
            File::deleteDirectory(storage_path('app') . '/Modules/');
            return response()->json(['status' => 200, 'message' => 'Module installed successfully'],200);
        }
        else
        {
            return response()->json(['status' => 500, 'message' => 'Module not vailed!'],500);
        }
    }
    public function validateModule($moduleName)
    {
        try
        {
            $uploadedModule = file_get_contents(storage_path('app') . '/Modules/'.$moduleName.'/module.json');
            $uploadedModule = json_decode($uploadedModule, true);

            if($uploadedModule['name'] == $moduleName)
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        catch (\Exception $e)
        {
            return false;
            \Log::error($e->getMessage());
        }
    }
    private function getZipName($filePath)
    {
        $array = explode('/', str_replace('\\', '/', $filePath));

        return end($array);
    }
}
