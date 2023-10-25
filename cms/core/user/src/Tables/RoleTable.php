<?php

namespace Dino\User\Tables;

use Dino\User\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleTable extends DataTable
{
    protected $table_id = 'roles-table';
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                if ($item->name === SUPERADMIN_ROLE_NAME) {
                    return '';
                }

                $actions = [];
                $tableId = $this->table_id;

                $actions['edit'] = route('role.admin.edit', $item->id);
                $actions['delete'] = route('role.admin.delete', $item->id);

                return view('core/base::table.action', compact('actions', 'tableId'))->render();
            })
            ->editColumn('is_default', function ($item) {
                return $item->is_default ? '<span class="badge bg-success bg-opacity-10 text-success">Mặc định</span>' : '';
            })
            ->editColumn('updated_at', function ($item) {
                return date('d/m/Y H:i:s', strtotime($item->updated_at));
            })
            ->editColumn('created_by', function ($item) {
                return $item->creator?->name;
            })
            ->setRowId('id')
            ->rawColumns(['is_default', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Dino\User\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->table_id)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->autoWidth(false)
            ->dom('<"datatable-header justify-content-start"f<"ms-sm-auto"><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ilp>')
            ->orderBy(0, 'asc')
            ->buttons([
                Button::make('create')->text('<i class="fas fa-plus"></i> Tạo mới')->addClass('btn-success')->addClass('btn-sm'),
                Button::make('reload')->addClass('btn-sm')->text('<i class="fas fa-sync"></i> Tải lại')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->width(100)->title('#'),
            Column::make('title')->title('Tiêu đề')->addClass('fw-semibold'),
            Column::make('description')->title('Mô tả'),
            Column::make('is_default')->title('Mặc định')->sortable(false)->addClass('text-center'),
            Column::make('created_by')->title('Tạo bởi'),
            Column::make('updated_at')->title('Cập nhật gần nhất'),
            Column::computed('action')
                ->title('Thao tác')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
        ];
    }
}
