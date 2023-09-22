<?php

namespace Dino\Base\Providers;

use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/base')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['common']);
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }
}
