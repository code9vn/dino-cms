@extends('core/base::layouts.auth')
@section('page_title', 'Đăng Nhập')

@section('content')
<form class="login-form" method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                    <img src="{{ asset('cms/images/logo_icon.svg') }}" class="h-48px"
                        alt="{{ config('core.base.common.app_name') }}">
                </div>
                <h5 class="mb-0">Đăng Nhập Vào Hệ Thống</h5>
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập/Email</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input name="login" type="text" class="form-control" placeholder="Tên đăng nhập/Email"
                        value="{{ old('login') }}" autocomplete="off">
                    <div class="form-control-feedback-icon">
                        <i class="ph-user-circle text-muted"></i>
                    </div>
                </div>
                @if ($errors->first('login'))
                <div class="form-text text-danger">{{ $errors->first('login') }}</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                </div>
                @if ($errors->first('password'))
                <div class="form-text text-danger">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="d-flex align-items-center mb-3">
                <label class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" checked>
                    <span class="form-check-label">Ghi nhớ?</span>
                </label>

                <a href="login_password_recover.html" class="ms-auto">Quên mật khẩu?</a>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">ĐĂNG NHẬP</button>
            </div>

            <span class="form-text text-center text-muted d-block">
                Developed by <a href="https://code9.vn" title="Code 9 VN" target="_blank">
                    <strong>Code 9</strong></a> with ❤️
                <br>
                Copyright &copy; {{ date('Y', time()) }}. All right reserved.
            </span>
        </div>
    </div>
</form>
@endsection
