<?php

namespace Dino\User\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }

        return view('core/user::auth.login');
    }

    public function doLogin(Request $request)
    {
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ], [
            'login.required' => 'Tên đăng nhập/Email không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
        ]);

        $request->merge([$field => $request->input('login')]);
        if (auth()->attempt($request->only($field, 'password'), $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'login' => 'Thông tin đăng nhập không đúng',
        ])->onlyInput('login');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
