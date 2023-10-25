@extends('core/base::layouts.master')
@section('page_title', 'Phân quyền')

@section('content')
<form method="POST"
    action="{{ ($role->id ?? '') ? route('role.admin.update', $role->id) : route('role.admin.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Tiêu đề</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Nhập tiêu đề" name="title"
                                value="{{ old('title', $role->title ?? '') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end">Mô tả</label>
                        <div class="col-lg-9">
                            <textarea name="description" class="form-control" placeholder="Nhập mô tả"
                                rows="3">{{ old('description', $role->description ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label text-lg-end"></label>
                        <div class="col-lg-9">
                            <div class="form-check form-check-inline form-switch">
                                <input type="checkbox" name="is_default" value="1" class="form-check-input"
                                    id="is_default" {{ old('is_default', $role->is_default ?? 0) == 1 ? 'checked' : ''
                                }}>
                                <label class="form-check-label" for="is_default">Mặc định</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('role.admin.index') }}" class="btn btn-dark">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary"><i class="ph-floppy-disk me-2"></i> Lưu lại</button>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quyền hạn</h6>
                </div>
                <div class="card-body list-permissions">
                    @if ($permissions)
                    <div class="accordion">
                        @foreach ($permissions as $parentFlag => $parentNode)
                        @if ($parentNode)
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#{{ str_replace('.', '_', $parentFlag) }}">
                                    {{ $parentNode['name'] }}
                                </button>
                            </h6>
                            <div id="{{ str_replace('.', '_', $parentFlag) }}" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    @if ($parentNode['children'])
                                    <div class="row list-style-none">
                                        @foreach ($parentNode['children'] as $childFlag => $childNode)
                                        <div class="col-md-3">
                                            <input type="checkbox" class="form-check-input me-1"
                                                id="{{ str_replace('.', '_', $childFlag) }}"
                                                name="permissions[]" value="{{ $childFlag }}">
                                            <label class="form-check-label"
                                                for="{{ str_replace('.', '_', $childFlag) }}">{{ $childNode }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')

<script type="text/javascript">
    $(document).ready(() => {
        var old_permissions = {!! json_encode(old('permissions', $oldPermissions ?? [])) !!};
        old_permissions.forEach(function(e){
            $('input[value="' + e + '"]').prop("checked", true);
        });
    });
</script>
@endpush
