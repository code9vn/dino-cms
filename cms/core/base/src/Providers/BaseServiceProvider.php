<?php

namespace Dino\Base\Providers;

use Dino\Base\Facades\BaseHelper;
use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Supports\Action;
use Dino\Base\Supports\Filter;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/base')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['common'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();


        $this->app->singleton('core:action', function () {
            return new Action();
        });

        $this->app->singleton('core:filter', function () {
            return new Filter();
        });

        $aliasLoader = AliasLoader::getInstance();

        if (!class_exists('BaseHelper')) {
            $aliasLoader->alias('BaseHelper', BaseHelper::class);
            $aliasLoader->alias('DashboardMenu', DashboardMenu::class);
        }
    }

    public function boot(): void
    {
        $this->app->setLocale(setting('admin_lang', 'vi'));
        $this->app['events']->listen(RouteMatched::class, function () {
            $this->registerDefaultMenus();
        });
    }

    /**
     * Add default dashboard menu for core
     */
    public function registerDefaultMenus(): void
    {
        DashboardMenu::make()
            ->registerItem([
                'id' => 'cms-core-system',
                'priority' => 999,
                'parent_id' => null,
                'name' => 'Thiết lập hệ thống',
                'icon' => 'ph-gear-six',
                'url' => route('setting.admin.index'),
                'permissions' => [],
            ])
            ->registerItem([
                'id' => 'cms-core-system-cache',
                'priority' => 4,
                'parent_id' => 'cms-core-system',
                'name' => 'Quản lý bộ nhớ đệm',
                'icon' => null,
                'url' => null,
                'permissions' => [],
            ]);
    }
}
