@extends('core/base::layouts.master')
@section('page_title', 'Quản lý giao diện')

@section('content')
@if (ThemeManager::getThemes())
<div class="row">
    @foreach (ThemeManager::getThemes() as $key => $theme)
    <div class="col-lg-3">
        <div
            class="card card-body @if (setting('theme') && Theme::getThemeName() == $key) bg-light border border-width-3 border-success @endif">
            <div class="d-sm-flex align-items-sm-start">
                <div class="flex-fill">
                    <img src="{{ Theme::getThemeScreenshot($key) }}" class="flex-shrink-0 rounded mb-1 w-100"
                        alt="{{ $theme['name'] }}">
                    <h6 class="mb-0">{{ $theme['name'] }}</h6>
                    <ul class="list-inline list-inline-bullet text-muted mb-1">
                        <li class="list-inline-item">
                            <small><a href="{{ $theme['url'] }}" target="_blank" class="text-dark"><span
                                        class="fw-semibold">{{ $theme['author'] }}</span></a></small>
                        </li>
                    </ul>
                    <p class="text-muted">{{ $theme['description'] }}</p>
                    @if (setting('theme') && Theme::getThemeName() == $key)
                    <span class="badge bg-success">Đã kích hoạt</span>
                    @else
                    <a href="javascript:;" class="btn btn-sm btn-info btn-trigger-update-theme"
                        data-theme="{{ $key }}">Kích hoạt</a>
                    <a href="javascript:;" class="btn btn-sm btn-link text-danger btn-trigger-remove-theme"
                        data-theme="{{ $key }}" data-name="{{ $theme['name'] }}">Gỡ bỏ</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-warning border-0">
    Chưa có giao diện nào được cài đặt
</div>
@endif
@endsection

@push('scripts')
<script type="text/javascript">
    $('.btn-trigger-update-theme').on('click', (e) => {
        if (!inProcess) {
            $.ajax({
                url: "{{ route('theme.admin.update') }}",
                method: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    theme: $(e.target).data('theme')
                },
                dataType: 'json',
                success: (res) => {
                    if (res.status) {
                        showNotify(res.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotify(res.message, 'error');
                    }
                },
                error: (err) => {
                    showNotify(err.responseJSON.message, 'error');
                }
            });
        }
    });

    $('.btn-trigger-remove-theme').on('click', (e) => {
        if (!inProcess) {
            swalInit.fire({
                title: 'Gỡ bỏ giao diện: <strong class="text-uppercase">' + $(e.target).data('name') + '</strong>',
                text: "Giao diện này và tệp tin liên quan sẽ bị gỡ bỏ hoàn toàn khỏi hệ thống. Hành động này không thể khôi phục, bạn có chắc chắn muốn gỡ bỏ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, hãy gỡ bỏ',
                cancelButtonText: 'Hủy bỏ',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-light'
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('theme.admin.remove') }}",
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            theme: $(e.target).data('theme')
                        },
                        dataType: 'json',
                        success: (res) => {
                            if (res.status) {
                                showNotify(res.message, 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showNotify(res.message, 'error');
                            }
                        },
                        error: (err) => {
                            showNotify(err.responseJSON.message, 'error');
                        }
                    });
                }
            });
        }
    });

</script>
@endpush
