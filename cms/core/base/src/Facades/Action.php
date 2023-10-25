<?php

namespace Dino\Base\Facades;

use Illuminate\Support\Facades\Facade;

class Action extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:action';
    }
}
