<?php

namespace Dino\Media\Providers;

use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        // ? CODE HERE
    }

    public function boot(): void
    {
        $this->setNamespace('core/media')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }
}
