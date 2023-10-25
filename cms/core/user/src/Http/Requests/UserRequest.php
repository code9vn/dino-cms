<?php

namespace Dino\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // if ($this->has('id') && !empty($this->id) && $this->id > 0)
        return [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'role' => 'required',
            'password' => 'required',
            'repassword' => 'required|same:password',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('core/user::user.form.name_required'),
            'username.required' => trans('core/user::user.form.username_required'),
            'username.unique' => trans('core/user::user.form.username_unique'),
            'email.required' => trans('core/user::user.form.email_required'),
            'email.unique' => trans('core/user::user.form.email_unique'),
            'password.required' => trans('core/user::user.form.password_required'),
            'repassword.required' => trans('core/user::user.form.repassword_required'),
            'repassword.same' => trans('core/user::user.form.repassword_same'),
            'role.required' => trans('core/user::user.form.role_required'),
        ];
    }
}
