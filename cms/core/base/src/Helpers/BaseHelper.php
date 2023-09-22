<?php

namespace Dino\Base\Helpers;

class BaseHelper
{
    public static function getAdminPrefix(): string
    {
        return config('core.base.common.admin_dir');
    }
}
