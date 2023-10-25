@extends('core/base::layouts.master')

@section('content')
<div class="card" style="overflow: hidden;">
    {{ $dataTable->table() }}
</div>
@endsection


@push('scripts')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script type="text/javascript">
    (function ($, DataTable) {

        // Datatable global configuration
        $.extend(true, DataTable.defaults, {
            language: {
                "emptyTable": "Không có dữ liệu",
                "sProcessing":   "Đang xử lý...",
                "sLengthMenu":   "Hiển thị  _MENU_ mục",
                "sZeroRecords":  "Không tìm thấy bản ghi nào phù hợp",
                "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
                "sInfoFiltered": "(được lọc từ _MAX_ mục)",
                "sInfoPostFix":  "",
                "sSearch":       "",
                "sSearchPlaceholder":       "Tìm kiếm...",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Đầu",
                    "sPrevious": "Trước",
                    "sNext":     "Tiếp",
                    "sLast":     "Cuối"
                }
            }
        });

    })(jQuery, jQuery.fn.dataTable);
</script>

{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
