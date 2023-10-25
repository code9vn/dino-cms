<?php

use Dino\Setting\Facades\Setting;
use Dino\Setting\Supports\SettingStore;

if (!function_exists('setting')) {
    function setting(string|null $key = null, $default = null)
    {
        if (!empty($key)) {
            try {
                return app(SettingStore::class)->get($key, $default);
            } catch (Throwable) {
                return $default;
            }
        }

        return Setting::getFacadeRoot();
    }
}
