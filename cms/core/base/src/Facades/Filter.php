<?php

namespace Dino\Base\Facades;

use Illuminate\Support\Facades\Facade;

class Filter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:filter';
    }
}
