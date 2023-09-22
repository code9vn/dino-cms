<?php

namespace Dino\Base\Facades;

use Dino\Base\Helpers\BaseHelper as BaseHelperSupport;
use Illuminate\Support\Facades\Facade;

class BaseHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseHelperSupport::class;
    }
}
