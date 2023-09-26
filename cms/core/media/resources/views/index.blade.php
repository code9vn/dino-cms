@extends('core/base::layouts.master')
@section('page_title', 'Quản Lý Tệp Tin')

@section('content')
<div style="height: calc(100vh - 135px);">
    <div id="fm" style="height: 100%;border-radius: 6px;overflow: hidden;"></div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('cms/libs/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<link rel="stylesheet" href="{{ asset('cms/css/fm-customize.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
    $(document).ready(() => {
        $('.contents').addClass('expanded');
        $('.overlay-dark-sidebar').addClass('show');
        $('.sidebar.sidebar-collapse').addClass('collapsed');
    });
</script>
@endpush
