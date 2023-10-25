<?php

namespace Dino\User\Repositories\Eloquent;

use Dino\Base\Supports\Repositories\RepositoriesAbstract;
use Dino\User\Models\User;
use Dino\User\Repositories\Interfaces\UserInterface;

class UserRepository extends RepositoriesAbstract implements UserInterface
{
    function model()
    {
        return User::class;
    }
}
