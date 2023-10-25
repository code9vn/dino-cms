<?php

namespace Dino\User\Providers;

use Dino\Base\Facades\DashboardMenu;
use Dino\Base\Supports\Helper;
use Dino\Base\Traits\LoadAndPublishDataTrait;
use Dino\User\Models\User;
use Dino\User\Repositories\Eloquent\PermissionRepository;
use Dino\User\Repositories\Eloquent\RoleRepository;
use Dino\User\Repositories\Eloquent\UserRepository;
use Dino\User\Repositories\Interfaces\PermissionInterface;
use Dino\User\Repositories\Interfaces\RoleInterface;
use Dino\User\Repositories\Interfaces\UserInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('core/user')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        config()->set('auth.providers.users.model', User::class);

        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermissionInterface::class, PermissionRepository::class);
    }

    public function boot(): void
    {
        // TODO: Kiểm tra và khởi tạo tài khoản Super Admin
        if (Helper::isConnectedDatabase()) {
            if (!app(RoleInterface::class)->where('name', SUPERADMIN_ROLE_NAME)->count()) {
                app(PermissionInterface::class)->create([
                    'name' => SUPERADMIN_ROLE_NAME,
                    'title' => 'Super Admin',
                    'description' => 'Quản trị tối cao',
                ]);
            }

            if (!app(UserInterface::class)->count()) {
                $firstUser = app(UserInterface::class)->create([
                    'username' => 'admin',
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('123456'),
                ]);

                $firstUser->assignRole(SUPERADMIN_ROLE_NAME);
            }
        }

        // TODO: Dashboard Menu
        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-core-user',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'Người dùng',
                'icon' => 'ph-users',
                'url' => route('user.admin.index'),
                'permissions' => [],
            ])
                ->registerItem([
                    'id' => 'cms-core-users',
                    'priority' => 1,
                    'parent_id' => 'cms-core-user',
                    'name' => 'Tài khoản',
                    'icon' => null,
                    'url' => route('user.admin.index'),
                    'permissions' => [],
                ])
                ->registerItem([
                    'id' => 'cms-core-role',
                    'priority' => 1,
                    'parent_id' => 'cms-core-user',
                    'name' => 'Phân quyền',
                    'icon' => null,
                    'url' => route('role.admin.index'),
                    'permissions' => [],
                ]);
        });
    }
}
