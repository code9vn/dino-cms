<?php

namespace Dino\Setting\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Dino\Setting\Facades\Setting;
use Dino\Setting\Repositories\Eloquent\SettingRepository;
use Dino\Setting\Repositories\Interfaces\SettingInterface;
use Dino\Setting\Supports\DatabaseSettingStore;
use Dino\Setting\Supports\SettingStore;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/setting')
            ->loadHelpers();

        $this->app->singleton(SettingStore::class, function () {
            return new DatabaseSettingStore();
        });

        $this->app->bind(SettingInterface::class, SettingRepository::class);

        if (!class_exists('Setting')) {
            AliasLoader::getInstance()->alias('Setting', Setting::class);
        }
    }

    public function boot(): void
    {
        $this->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-core-system-setting',
                'priority' => 0,
                'parent_id' => 'cms-core-system',
                'name' => 'Cấu hình chung',
                'icon' => null,
                'url' => route('setting.admin.index'),
                'permissions' => [],
            ]);
        });
    }

    public function provides()
    {
        return [
            SettingStore::class
        ];
    }
}
