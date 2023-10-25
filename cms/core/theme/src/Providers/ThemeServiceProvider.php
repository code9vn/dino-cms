<?php

namespace Dino\Theme\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/theme')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this->loadAndPublishViews()
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'public']);

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-core-system-theme',
                'priority' => 2,
                'parent_id' => 'cms-core-system',
                'name' => 'Quản lý giao diện',
                'icon' => null,
                'url' => route('theme.admin.index'),
                'permissions' => [],
            ]);
        });

        $this->app->register(ThemeLoaderServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }
}
