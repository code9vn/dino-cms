<?php

namespace Dino\Base\Traits;

use Dino\Base\Supports\Helper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

trait LoadAndPublishDataTrait
{
    protected string|null $namespace = null;

    protected function setNamespace(string $namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');

        $this->app['config']->set(['core.base.common.module_namespaces.' . File::basename($this->getPath()) => $namespace]);

        return $this;
    }

    protected function getPath(string $path = null): string
    {
        $reflection = new ReflectionClass($this);

        $modulePath = str_replace('/src/Providers', '', File::dirname($reflection->getFilename()));

        if (!Str::contains($modulePath, base_path('cms/modules'))) {
            $modulePath = base_path('cms/' . $this->getDashedNamespace());
        }

        return $modulePath . ($path ? '/' . ltrim($path, '/') : '');
    }

    protected function loadAndPublishConfigurations(array|string $fileNames): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->mergeConfigFrom($this->getConfigFilePath($fileName), $this->getDotedNamespace() . '.' . $fileName);
        }

        return $this;
    }

    protected function getConfigFilePath(string $file): string
    {
        return $this->getPath('config/' . $file . '.php');
    }

    protected function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDashedNamespace());
        return $this;
    }

    protected function getViewsPath(): string
    {
        return $this->getPath('/resources/views');
    }

    public function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        return $this;
    }

    protected function getTranslationsPath(): string
    {
        return $this->getPath('/resources/lang');
    }

    protected function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());

        return $this;
    }

    protected function getMigrationsPath(): string
    {
        return $this->getPath('/database/migrations');
    }

    protected function loadRoutes(array|string $fileNames = ['web']): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->loadRoutesFrom($this->getRouteFilePath($fileName));
        }

        return $this;
    }

    protected function getRouteFilePath(string $file): string
    {
        return $this->getPath('routes/' . $file . '.php');
    }

    protected function publishAssets(string $path = null): self
    {
        if (empty($path)) {
            $path = 'vendor/core/' . $this->getDashedNamespace();
        }

        $this->publishes([$this->getAssetsPath() => public_path($path)], 'cms-public');

        return $this;
    }

    protected function getAssetsPath(): string
    {
        return $this->getPath('public');
    }

    protected function loadHelpers(): self
    {
        Helper::autoload($this->getPath('/helpers'));

        return $this;
    }

    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }
}
