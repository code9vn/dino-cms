<?php

namespace Dino\Module\Services;

use Composer\Autoload\ClassLoader;
use Dino\Base\Facades\BaseHelper;
use Dino\Base\Services\ClearCacheService;
use Dino\Base\Supports\Helper;
use Dino\Setting\Facades\Setting;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ModuleService
{

    function __construct(protected Application $app, protected Filesystem $files)
    {
    }

    public function activate(string $module): array
    {
        $validate = $this->validate($module);

        if ($validate['error']) {
            return $validate;
        }

        $content = $this->getModuleInfo($module);

        $moduleName = Arr::get($content, 'name') ?? Str::studly($module);

        if (empty($content)) {
            return [
                'error' => true,
                'message' => trans('core/module::module.invalid_json'),
            ];
        }

        $minimumCoreVersion = Arr::get($content, 'minimum_core_version');
        $coreVersion = get_core_version();

        if ($minimumCoreVersion && (version_compare($coreVersion, $minimumCoreVersion, '<'))) {
            return [
                'error' => true,
                'message' => trans('core/module::module.minimum_core_version_not_met', [
                    'module' => $moduleName,
                    'minimum_core_version' => $minimumCoreVersion,
                    'current_core_version' => $coreVersion,
                ]),
            ];
        }

        if (!Arr::get($content, 'ready', 1)) {
            return [
                'error' => true,
                'message' => trans(
                    'core/module::module.module_is_not_ready',
                    ['name' => $moduleName]
                ),
            ];
        }

        $this->clearCache();

        $activatedModules = get_active_modules();
        if (!in_array($module, $activatedModules)) {
            $requiredModules = $this->getDependencies($module);

            if ($missingModules = array_diff($requiredModules, $activatedModules)) {
                return [
                    'error' => true,
                    'message' => trans(
                        'core/module::module.missing_required_modules',
                        ['modules' => implode(',', $missingModules)]
                    ),
                ];
            }

            if (!class_exists($content['provider'])) {
                $loader = new ClassLoader();
                $loader->setPsr4($content['namespace'], module_path($module . '/src'));
                $loader->register(true);

                $this->app->register($content['provider']);

                if (class_exists($content['namespace'] . 'Module')) {
                    call_user_func([$content['namespace'] . 'Module', 'activate']);
                }

                $this->runMigrations($module);
            }

            $activatedModules = array_merge($activatedModules, [$module]);

            $this->saveActivatedModules($activatedModules);

            if (class_exists($content['namespace'] . 'Module')) {
                call_user_func([$content['namespace'] . 'Module', 'activated']);
            }

            $this->clearCache();

            return [
                'error' => false,
                'message' => trans('core/module::module.activate_success'),
            ];
        }

        return [
            'error' => true,
            'message' => trans('core/module::module.activated_already'),
        ];
    }

    public function deactivate(string $module): array
    {
        $validate = $this->validate($module);

        if ($validate['error']) {
            return $validate;
        }

        $content = $this->getModuleInfo($module);

        $this->clearCache();

        if (!class_exists($content['provider'])) {
            $loader = new ClassLoader();
            $loader->setPsr4($content['namespace'], module_path($module . '/src'));
            $loader->register(true);
        }

        $activatedModules = get_active_modules();
        if (in_array($module, $activatedModules)) {
            if (class_exists($content['namespace'] . 'Module')) {
                call_user_func([$content['namespace'] . 'Module', 'deactivate']);
            }

            if (($key = array_search($module, $activatedModules)) !== false) {
                unset($activatedModules[$key]);
            }

            $this->saveActivatedModules($activatedModules);

            if (class_exists($content['namespace'] . 'Module')) {
                call_user_func([$content['namespace'] . 'Module', 'deactivated']);
            }

            $this->clearCache();

            return [
                'error' => false,
                'message' => trans('core/module::module.deactivated_success'),
            ];
        }

        return [
            'error' => true,
            'message' => trans('core/module::module.deactivated_already'),
        ];
    }

    public function remove(string $module): array
    {
        $validate = $this->validate($module);

        if ($validate['error']) {
            return $validate;
        }

        $this->clearCache();

        $this->deactivate($module);

        $content = $this->getModuleInfo($module);

        if (!empty($content)) {
            if (!class_exists($content['provider'])) {
                $loader = new ClassLoader();
                $loader->setPsr4($content['namespace'], module_path($module . '/src'));
                $loader->register(true);
            }

            Schema::disableForeignKeyConstraints();
            if (class_exists($content['namespace'] . 'Module')) {
                call_user_func([$content['namespace'] . 'Module', 'remove']);
            }
            Schema::enableForeignKeyConstraints();
        }

        $location = module_path($module);

        $migrations = [];
        foreach (BaseHelper::scanFolder($location . '/database/migrations') as $file) {
            $migrations[] = pathinfo($file, PATHINFO_FILENAME);
        }

        DB::table('migrations')->whereIn('migration', $migrations)->delete();

        $this->files->deleteDirectory($location);

        if (empty($this->files->directories(module_path()))) {
            $this->files->deleteDirectory(module_path());
        }

        // Helper::removeModuleFiles(Str::afterLast($this->getPluginNamespace($module), '/'), 'plugins');

        if (class_exists($content['namespace'] . 'Module')) {
            call_user_func([$content['namespace'] . 'Module', 'removed']);
        }

        $this->clearCache();

        return [
            'error' => false,
            'message' => trans('core/module::module.module_removed'),
        ];
    }

    protected function validate(string $module): array
    {
        $location = module_path($module);

        if (!$this->files->isDirectory($location)) {
            return [
                'error' => true,
                'message' => trans('core/module::module.module_not_exist'),
            ];
        }

        if (!$this->getModuleInfo($module)) {
            return [
                'error' => true,
                'message' => trans('core/module::module.missing_json_file'),
            ];
        }

        return [
            'error' => false,
            'message' => trans('core/module::module.module_invalid'),
        ];
    }

    public function validateModule(string $module, bool $throw = false): bool
    {
        $content = $this->getModuleInfo($module);

        $rules = [
            'id' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'namespace' => ['required', 'string', 'max:200'],
            'provider' => ['required', 'string', 'max:250'],
            'author' => ['nullable', 'string', 'max:120'],
            'url' => ['nullable', 'string', 'url', 'max:255'],
            'version' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:400'],
            'require' => ['nullable', 'array', 'max:10'],
            'minimum_core_version' => ['nullable', 'string', 'regex:/^[0-9]+\.[0-9]+\.[0-9]+$/'],
        ];

        if ($throw) {
            $rules['id'] = ['required', 'string', 'max:100'];
        }

        $validator = Validator::make($content, $rules);

        $passes = $validator->passes();

        if (!$passes) {
            logger()->info($validator->getMessageBag()->toJson());

            if ($throw) {
                throw new Exception($validator->getMessageBag()->toJson());
            }
        }

        return $passes;
    }

    protected function saveActivatedModules(array $modules): array
    {
        $modules = array_values($modules);

        $availableModules = BaseHelper::scanFolder(module_path());

        $modules = array_intersect($modules, $availableModules);

        Setting::set('activated_modules', json_encode($modules))->save();

        return $modules;
    }

    public function getModuleInfo(string $module): array
    {
        $jsonFilePath = module_path($module . '/module.json');

        if (!$this->files->exists($jsonFilePath)) {
            return [];
        }

        return BaseHelper::getFileData($jsonFilePath);
    }

    public function runMigrations(string $module): void
    {
        $migrationPath = module_path($module . '/database/migrations');

        if (!$this->files->isDirectory($migrationPath)) {
            return;
        }

        $this->app['migrator']->run($migrationPath);
    }

    public function getDependencies(string $module): array
    {
        $module = strtolower($module);

        $content = $this->getModuleInfo($module);
        $requiredModules = $content['require'] ?? [];

        $activatedModules = get_active_modules();

        foreach ($requiredModules as $key => $requiredModule) {
            if (in_array(Arr::last(explode('/', $requiredModule)), $activatedModules)) {
                unset($requiredModules[$key]);
            }
        }

        return $requiredModules;
    }

    public function clearCache(): void
    {
        Helper::clearCache();
        ClearCacheService::make()->clearConfig();
    }
}
