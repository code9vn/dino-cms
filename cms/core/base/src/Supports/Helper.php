<?php

namespace Dino\Base\Supports;

use Illuminate\Support\Facades\File;

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
}
