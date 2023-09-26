<?php

namespace Dino\User\Providers;

use Dino\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        // ? CODE HERE
    }

    public function boot(): void
    {
        $this->setNamespace('core/user')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();
    }
}
