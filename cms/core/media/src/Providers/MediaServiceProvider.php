<?php

namespace Dino\Media\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/media')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }

    public function boot(): void
    {
        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-core-media',
                'priority' => 100,
                'parent_id' => null,
                'name' => 'Quản lý tệp tin',
                'icon' => 'ph-files',
                'url' => route('media.admin.index'),
                'permissions' => [],
            ]);
        });
    }
}
