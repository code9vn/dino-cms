<?php

namespace Dino\User\Repositories\Interfaces;

use Dino\Base\Supports\Repositories\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    public function getAvailablePermissions(): array;
}
