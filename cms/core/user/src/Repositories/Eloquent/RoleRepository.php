<?php

namespace Dino\User\Repositories\Eloquent;

use Dino\Base\Facades\BaseHelper;
use Dino\Base\Supports\Repositories\RepositoriesAbstract;
use Dino\User\Models\Role;
use Dino\User\Repositories\Interfaces\RoleInterface;

class RoleRepository extends RepositoriesAbstract implements RoleInterface
{
    function model()
    {
        return Role::class;
    }

    public function getAvailablePermissions(): array
    {
        $permissions = [];

        // CORE Folder
        foreach (BaseHelper::scanFolder(cms_path('core')) as $module) {
            $configuration = config(strtolower('core.' . $module . '.permissions'));
            if (!empty($configuration)) {
                foreach ($configuration as $config) {
                    $permissions[$config['flag']] = $config;
                }
            }
        }

        // MODULES Folder
        foreach (BaseHelper::scanFolder(cms_path('modules')) as $module) {
            $configuration = config(strtolower('module.' . $module . '.permissions'));
            if (!empty($configuration)) {
                foreach ($configuration as $config) {
                    $permissions[$config['flag']] = $config;
                }
            }
        }

        return $permissions;
    }
}
