<?php

use Dino\Base\Facades\BaseHelper;

if (!function_exists('is_in_admin')) {
    function is_in_admin(bool $force = false): bool
    {
        $prefix = BaseHelper::getAdminPrefix();

        $segments = array_slice(request()->segments(), 0, count(explode('/', $prefix)));

        $isInAdmin = implode('/', $segments) === $prefix;

        return $force ? $isInAdmin : apply_filters(IS_IN_ADMIN_FILTER, $isInAdmin);
    }
}

if (!function_exists('cms_path')) {
    function cms_path(string|null $path = null): string
    {
        return base_path('cms' . DIRECTORY_SEPARATOR . $path);
    }
}

if (!function_exists('get_core_version')) {
    function get_core_version(): string
    {
        return '0.0.01';
    }
}
