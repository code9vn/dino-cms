const tooltipSelector = document.querySelectorAll('[data-bs-popup="tooltip"]');

tooltipSelector.forEach(function (popup) {
    new bootstrap.Tooltip(popup);
});

const swalInit = swal.mixin({
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-light',
        denyButton: 'btn btn-light',
        input: 'form-control'
    },
    position: 'top'
});

function action_delete(element) {
    if (!inProcess) {
        swalInit.fire({
            title: 'Xóa dữ liệu',
            text: "Dữ liệu sẽ không thể khôi phục. Bạn có chắc chắn muốn xóa?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Vâng, hãy xóa',
            cancelButtonText: 'Hủy bỏ',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-light'
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: $(element).data("href"),
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: (res) => {
                        if (res.status) {
                            showNotify(res.message, 'success');
                            window.LaravelDataTables[$(element).data('table')].ajax.reload();
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
}
