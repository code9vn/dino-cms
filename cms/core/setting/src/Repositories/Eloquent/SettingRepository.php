<?php

namespace Dino\Setting\Repositories\Eloquent;

use Dino\Base\Supports\Repositories\RepositoriesAbstract;
use Dino\Setting\Models\Setting;
use Dino\Setting\Repositories\Interfaces\SettingInterface;

class SettingRepository extends RepositoriesAbstract implements SettingInterface
{
    function model()
    {
        return Setting::class;
    }
}
