<?php

namespace Dino\User\Repositories\Eloquent;

use Dino\Base\Supports\Repositories\RepositoriesAbstract;
use Dino\User\Models\Permission;
use Dino\User\Repositories\Interfaces\PermissionInterface;

class PermissionRepository extends RepositoriesAbstract implements PermissionInterface
{
    function model()
    {
        return Permission::class;
    }
}
