@extends('core/base::layouts.master')
@section('page_title', 'Tài khoản')

@section('content')
<form method="POST"
    action="{{ ($user->id ?? '') ? route('user.admin.update', $user->id) : route('user.admin.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Tên hiển thị <sup
                                class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Tên hiển thị" name="name"
                                value="{{ old('name', $user->name ?? '') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Tên đăng nhập <sup
                                class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username"
                                value="{{ old('username', $user->username ?? '') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Email <sup class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                value="{{ old('email', $user->email ?? '') }}">
                        </div>
                    </div>
                    @if (($user->id ?? '') === '')
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Mật khẩu <sup
                                class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Mật khẩu" name="password"
                                autocomplete="new-password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Nhập lại mật khẩu <sup
                                class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Nhập lại mật khẩu"
                                name="repassword" autocomplete="new-password">
                        </div>
                    </div>
                    @else
                    {{-- Sửa tài khoản --}}
                    @endif
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('user.admin.index') }}" class="btn btn-dark">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary"><i class="ph-floppy-disk me-2"></i> Lưu lại</button>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Phân quyền <sup
                                class="required">(∗)</sup></label>
                        <div class="col-lg-9">
                            <select name="role" class="form-select">
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ (old('role')===$role->name) ? 'selected' :
                                    (old('role', '') === '' && $role->is_default ? 'selected' : '') }}>{{ $role->title
                                    }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
