<?php

namespace Dino\Setting\Facades;

use Dino\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingStore::class;
    }
}
