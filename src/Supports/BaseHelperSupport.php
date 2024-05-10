<?php
namespace Weoneteam\PluginManager\Supports;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Throwable;

class BaseHelperSupport
{
    public function scanFolder(string $path, array $ignoreFiles = []): array
    {
        if (! $path) {
            return [];
        }

        if (File::isDirectory($path)) {
            $data = array_diff(scandir($path), array_merge(['.', '..', '.DS_Store'], $ignoreFiles));
            natsort($data);

            return $data;
        }

        return [];
    }
    public function getFileData(string $file, bool $convertToArray = true)
    {
        $file = File::get($file);
        if (! empty($file)) {
            if ($convertToArray) {
                return json_decode($file, true);
            }

            return $file;
        }

        if (! $convertToArray) {
            return null;
        }

        return [];
    }
}
