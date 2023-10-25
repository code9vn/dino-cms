@extends('core/base::layouts.master')
@section('page_title', 'Cấu hình chung')

@section('content')
<form method="post" action="{{ route('setting.admin.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Thông tin website</h6>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Tên website</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Nhập tên website" name="site_name" value="{{ old('site_name', setting('site_name')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Mô tả ngắn</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Nhập mô tả ngắn website" name="site_description" value="{{ old('site_description', setting('site_description')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Từ khóa tìm kiếm</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Nhập từ khóa cho máy chủ tìm kiếm" name="site_keywords" value="{{ old('site_keywords', setting('site_keywords')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Email</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Nhập email của website" name="site_email" value="{{ old('site_email', setting('site_email')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Điện thoại</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Nhập điện thoại của website" name="site_phone" value="{{ old('site_phone', setting('site_phone')) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Cấu hình gửi mail</h6>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Máy chủ (SMTP) gửi đi</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="smtp.gmail.com" name="mail_host" value="{{ old('mail_host', setting('mail_host')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Cổng gửi mail</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" style="width: 120px" placeholder="587 | 465" name="mail_port" value="{{ old('mail_port', setting('mail_port')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Sử dụng xác thực</label>
                <div class="col-lg-9">
                    <select name="mail_encryption" class="form-select" style="width: 120px">
                        <option value="none" {{ old('mail_encryption', setting('mail_encryption')) == 'none' ? 'selected' : '' }}>Không</option>
                        <option value="ssl" {{ old('mail_encryption', setting('mail_encryption')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="tls" {{ old('mail_encryption', setting('mail_encryption')) == 'tls' ? 'selected' : '' }}>TLS</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Tài khoản</label>
                <div class="col-lg-9">
                    <input type="text" autocomplete="new-password" class="form-control" placeholder="user@gmail.com" name="mail_username" value="{{ old('mail_username', setting('mail_username')) }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Mật khẩu</label>
                <div class="col-lg-9">
                    <input type="password" autocomplete="new-password" class="form-control" placeholder="******" name="mail_password" value="{{ old('mail_password', setting('mail_password')) }}">
                </div>
            </div>
            <hr class="border-bottom">
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Tên người gửi</label>
                <div class="col-lg-9">
                    <input type="text" autocomplete="new-password" class="form-control" placeholder="Nhập tên người gửi" name="mail_sender_name" value="{{ old('mail_sender_name', setting('mail_sender_name')) }}">
                    <div class="form-text text-muted">Để trống hệ thống sẽ lấy tên website</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label text-lg-end">Email người gửi</label>
                <div class="col-lg-9">
                    <input type="text" autocomplete="new-password" class="form-control" placeholder="user@gmail.com" name="mail_sender_email" value="{{ old('mail_sender_email', setting('mail_sender_email')) }}">
                    <div class="form-text text-muted">Để trống hệ thống sẽ lấy email của website</div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="ph-floppy-disk me-2"></i> Lưu cấu hình</button>
    </div>
</form>
@endsection
