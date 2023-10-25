<ul class="datatable_actions mb-0 d-flex flex-wrap">
    @if (Arr::has($actions, 'edit'))
    <li>
        <a href="{{ $actions['edit'] }}" class="text-info edit" data-bs-popup="tooltip" data-bs-placement="top" title="Sửa">
            <i class="fas fa-edit"></i>
        </a>
    </li>
    @endif
    @if (Arr::has($actions, 'delete'))
    <li>
        <a href="javascript:;" onclick="action_delete(this);return false;" data-table="{{ $tableId ?? '' }}"
            data-href="{{ $actions['delete'] }}" class="text-danger remove" data-bs-popup="tooltip" data-bs-placement="top" title="Xóa">
            <i class="fas fa-trash-alt"></i>
        </a>
    </li>
    @endif
</ul>
