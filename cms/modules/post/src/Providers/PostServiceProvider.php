<?php

namespace Dino\Post\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->setNamespace('module/post')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-module-post',
                'priority' => 2,
                'parent_id' => null,
                'name' => 'Bài viết',
                'icon' => 'ph-article',
                'url' => null,
                'permissions' => [],
            ]);
        });
    }
}
