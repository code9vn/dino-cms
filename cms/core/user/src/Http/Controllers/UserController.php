<?php

namespace Dino\User\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;
use Dino\User\Http\Requests\UserRequest;
use Dino\User\Repositories\Interfaces\RoleInterface;
use Dino\User\Repositories\Interfaces\UserInterface;
use Dino\User\Tables\UserTable;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{

    function __construct(
        protected RoleInterface $roleRepository,
        protected UserInterface $userRepository
    ) {
    }
    public function index(UserTable $dataTable)
    {
        return $dataTable->render('core/base::table.table');
    }

    public function create()
    {
        $roles = $this->roleRepository->all();
        return view('core/user::form_user', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->except('_token');

        $data['password'] = Hash::make($request->input('password', '123456'));

        try {
            $user = $this->userRepository->create($data);
            if ($user) {
                $user->assignRole($request->input('role'));
                if ($request->input('role') == SUPERADMIN_ROLE_NAME) {
                    $user->syncPermissions(null);
                }

                return redirect()->route('user.admin.index')->with('flash_data', [
                    'type' => 'success',
                    'message' => trans('core/user::user.created_user_success')
                ]);
            }
        } catch (Exception $error) {
            return redirect()->route('user.admin.create')->withInput()->with('flash_data', [
                'type' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $user = $this->userRepository->with('roles')->find($id);
        if ($user) {
            $roles = $this->roleRepository->all();
            return view('core/user::form_user', compact('user', 'roles'));
        }
        return abort(404);
    }
}
