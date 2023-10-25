<?php

namespace Dino\User\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;
use Dino\User\Http\Requests\RoleRequest;
use Dino\User\Repositories\Interfaces\PermissionInterface;
use Dino\User\Repositories\Interfaces\RoleInterface;
use Dino\User\Tables\RoleTable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class RoleController extends BaseController
{
    function __construct(
        protected RoleInterface $roleRepository,
        protected PermissionInterface $permissionRepository
    ) {
        // ! SYNC PERMISSIONS
        $permissions = $this->roleRepository->getAvailablePermissions();
        $permissions = array_keys($permissions);
        if ($permissions) {
            // TODO: Remove permissions not in array
            $permissionRepository->whereNotIn('name', $permissions)->delete();
            // TODO: Check if no existed => create permission
            foreach ($permissions as $permission) {
                if (!$permissionRepository->where('name', $permission)->count()) {
                    $permissionRepository->create(['name' => $permission]);
                }
            }
        }
    }

    public function index(RoleTable $dataTable)
    {
        return $dataTable->render('core/base::table.table');
    }

    public function create()
    {
        $permissions = $this->getPermissionTree($this->roleRepository->getAvailablePermissions());
        return view('core/user::form_role', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $data = $request->except('_token');
        $data['name'] = Str::slug($data['title']);
        $data['created_by'] = auth()->id();
        if (!$this->roleRepository->where('name', $data['name'])->count()) {
            $role = $this->roleRepository->create($data);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions(null);
            }
            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('role.admin.index')->with('flash_data', [
                'type' => 'success',
                'message' => trans('core/user::role.created_role_success')
            ]);
        } else {
            return redirect()->route('role.admin.create')->withInput()->with('flash_data', [
                'type' => 'error',
                'message' => trans('core/user::role.existed_role_name')
            ]);
        }
    }

    public function edit($id)
    {
        $role = $this->roleRepository->with('permissions')->find($id);
        if ($role) {
            $permissions = $this->getPermissionTree($this->roleRepository->getAvailablePermissions());
            $oldPermissions = $role->permissions->pluck('name')->toArray();
            return view('core/user::form_role', compact('role', 'permissions', 'oldPermissions'));
        }
        return abort(404);
    }

    public function update($id, RoleRequest $request)
    {
        $role = $this->roleRepository->find($id);
        $role->update($request->except('_token'));

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions(null);
        }
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions(null);
        }
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('role.admin.index')->with('flash_data', ['type' => 'success', 'message' => 'Cập nhật phân quyền thành công']);
    }

    public function delete($id, Request $request)
    {
        $role = $this->roleRepository->find($id);
        $role->syncPermissions(null);
        $role->delete();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Xóa phân quyền thành công'
            ]);
        }
        return redirect()->route('role.admin.index')->with('flash_data', ['type' => 'success', 'message' => 'Xóa phân quyền thành công']);
    }

    protected function getPermissionTree(array $permissions): array
    {
        $sortedFlag = $permissions;
        sort($sortedFlag);
        $children['root'] = $this->getChildren('root', $sortedFlag);

        foreach (array_keys($permissions) as $key) {
            $childrenReturned = $this->getChildren($key, $permissions);
            if (count($childrenReturned) > 0) {
                $children[$key] = [
                    'name' => $permissions[$key]['name'],
                    'children' => $childrenReturned
                ];
            }
        }

        return $children;
    }

    protected function getChildren(string $parentFlag, array $allFlags): array
    {
        $newFlagArray = [];
        foreach ($allFlags as $flagDetails) {
            if (Arr::get($flagDetails, 'parent_flag', 'root') == $parentFlag) {
                $newFlagArray[$flagDetails['flag']] = $flagDetails['name'];
            }
        }

        return $newFlagArray;
    }
}
