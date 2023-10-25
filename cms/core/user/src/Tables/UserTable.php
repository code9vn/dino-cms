<?php

namespace Dino\User\Tables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Dino\User\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserTable extends DataTable
{
    protected $table_id = 'users-table';
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
                $actions = [];
                $table_id = $this->table_id;

                $actions['edit'] = route('user.admin.edit', $item->id);
                if ($item->id !== auth()->id()) {
                    $actions['delete'] = route('user.admin.delete', $item->id);
                }

                return view('core/base::table.action', compact('actions', 'table_id'))->render();
            })
            ->editColumn('roles', function ($item) {
                $roles = $item->roles->pluck('title')->toArray();
                return implode(', ', $roles);
            })
            ->editColumn('updated_at', function ($item) {
                return date('d/m/Y H:i:s', strtotime($item->updated_at));
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Dino\User\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
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
            // ->ajax()
            ->autoWidth(false)
            ->dom('<"datatable-header justify-content-start"f<"ms-sm-auto"><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ilp>')
            ->orderBy(0)
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
            Column::make('username')->title('Tài khoản')->addClass('fw-semibold'),
            Column::make('email'),
            Column::make('name')->title('Tên'),
            Column::computed('roles')->title('Phân quyền'),
            Column::make('updated_at')->title('Cập nhật gần nhất'),
            Column::computed('action')
                ->title('Thao tác')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
