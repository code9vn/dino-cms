<?php

namespace Dino\Page\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('module/page')
            ->loadHelpers();
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
}
