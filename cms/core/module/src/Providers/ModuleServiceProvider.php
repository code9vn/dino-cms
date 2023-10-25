<?php

namespace Dino\Module\Providers;

use Composer\Autoload\ClassLoader;
use Dino\Base\Facades\BaseHelper;
use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/module')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }

    public function boot(): void
    {
        $loader = new ClassLoader();

        $activatedModules = get_active_modules();

        foreach ($activatedModules as $module) {
            $modulePath = module_path($module);
            if (!File::isDirectory($modulePath) || !File::exists($modulePath . DIRECTORY_SEPARATOR . 'module.json')) {
                continue;
            }

            $content = BaseHelper::getFileData($modulePath . DIRECTORY_SEPARATOR . 'module.json');
            if (!empty($content)) {
                $loader->setPsr4($content['namespace'], module_path($module . '/src'));
                $loader->register();
                if (!class_exists($content['provider'])) {
                    continue;
                }

                $this->app->register($content['provider']);
            }
        }



        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-core-module',
                'priority' => 3,
                'parent_id' => 'cms-core-system',
                'name' => 'Quản lý module',
                'icon' => null,
                'url' => route('module.admin.index'),
                'permissions' => [],
            ]);
        });
    }
}
