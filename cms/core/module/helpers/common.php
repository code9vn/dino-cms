<?php

use Dino\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\File;

if (!function_exists('module_path')) {
    function module_path(string|null $path = null): string
    {
        return cms_path('modules' . DIRECTORY_SEPARATOR . $path);
    }
}

if (!function_exists('is_module_active')) {
    function is_module_active(string $alias): bool
    {
        return in_array($alias, get_active_modules());
    }
}

if (!function_exists('get_active_modules')) {
    function get_active_modules(): array
    {
        $modules = array_unique(json_decode(setting('activated_modules', '[]'), true));

        $existingModules = BaseHelper::scanFolder(module_path());

        return array_diff($modules, array_diff($modules, $existingModules));
    }
}

if (!function_exists('get_installed_modules')) {
    function get_installed_modules(): array
    {
        $list = [];
        $modules = BaseHelper::scanFolder(module_path());

        if (!empty($modules)) {
            foreach ($modules as $module) {
                $path = module_path($module);
                if (!File::isDirectory($path) || !File::exists($path . DIRECTORY_SEPARATOR . 'module.json')) {
                    continue;
                }

                $list[] = $module;
            }
        }

        return $list;
    }
}
