<?php

namespace Dino\Base\Supports;

use Dino\Base\Services\ClearCacheService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Helper
{
    public static function autoload(string $directory): void
    {
        $helpers = File::glob($directory . '/*.php');

        if (empty($helpers) || !is_array($helpers)) {
            return;
        }

        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    public static function isConnectedDatabase(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (Throwable) {
            return false;
        }
    }

    public static function clearCache(): bool
    {
        $clearCacheService = ClearCacheService::make();

        $clearCacheService->clearFrameworkCache();
        $clearCacheService->clearBootstrapCache();
        $clearCacheService->clearRoutesCache();
        $clearCacheService->clearPurifier();
        $clearCacheService->clearDebugbar();

        return true;
    }
}
