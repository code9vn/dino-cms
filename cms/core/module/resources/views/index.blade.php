@extends('core/base::layouts.master')
@section('page_title', 'Quản lý module')

@section('content')
@if ($list)
<div class="row">
    @foreach ($list as $item)
    <div class="col-lg-3">
        <div class="card card-body">
            <div class="d-sm-flex align-items-sm-start">
                <div class="flex-fill">
                    <img src="{{ $item->image }}" class="flex-shrink-0 rounded mb-1" height="45"
                        alt="{{ $item->name }}">
                    <h6 class="mb-0">{{ $item->name }}</h6>
                    <ul class="list-inline list-inline-bullet text-muted mb-1">
                        <li class="list-inline-item">
                            <small><a href="{{ $item->url }}" target="_blank" class="text-dark"><span
                                        class="fw-semibold">{{ $item->author }}</span></a></small>
                        </li>
                        <li class="list-inline-item"><small>{{ $item->version }}</small></li>
                    </ul>
                    <p class="text-muted">{{ $item->description }}</p>
                    @if ($item->status)
                    <a href="javascript:;" class="btn btn-sm btn-yellow btn-trigger-update-module"
                        data-module="{{ $item->path }}">Hủy kích hoạt</a>
                    @else
                    <a href="javascript:;" class="btn btn-sm btn-info btn-trigger-update-module"
                        data-module="{{ $item->path }}">Kích hoạt</a>
                    @endif
                    <a href="javascript:;" class="btn btn-sm btn-success d-none" data-module="{{ $item->path }}">Nâng
                        cấp</a>
                    <a href="javascript:;" class="btn btn-sm btn-link text-danger btn-trigger-remove-module"
                        data-module="{{ $item->path }}" data-name="{{ $item->name }}">Gỡ bỏ</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-warning border-0">
    Chưa có module nào được cài đặt
</div>
@endif
@endsection

@push('scripts')
<script type="text/javascript">
    $('.btn-trigger-update-module').on('click', (e) => {
        if (!inProcess) {
            $.ajax({
                url: "{{ route('module.admin.update') }}",
                method: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: $(e.target).data('module')
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

    $('.btn-trigger-remove-module').on('click', (e) => {
        if (!inProcess) {
            swalInit.fire({
                title: 'Gỡ module: <strong class="text-uppercase">' + $(e.target).data('name') + '</strong>',
                text: "Module này và các chức năng liên quan sẽ bị gỡ bỏ hoàn toàn khỏi hệ thống. Hành động này không thể khôi phục, bạn có chắc chắn muốn gỡ bỏ?",
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
                        url: "{{ route('module.admin.remove') }}",
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            name: $(e.target).data('module')
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
