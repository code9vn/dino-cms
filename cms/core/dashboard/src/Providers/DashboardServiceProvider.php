<?php

namespace Dino\Dashboard\Providers;

use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        // ? CODE HERE
    }

    public function boot(): void
    {
        $this->setNamespace('core/dashboard')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }
}
