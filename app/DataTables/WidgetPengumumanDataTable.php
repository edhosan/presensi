<?php

namespace App\DataTables;

use App\Model\WidgetPengumuman;
use Yajra\Datatables\Services\DataTable;

class WidgetPengumumanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'widgetpengumumandatatable.action');
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = WidgetPengumuman::query()->select($this->getColumns());

        return $this->applyScopes($query);
    }   

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'title',
            'filename',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'widgetpengumumandatatable_' . time();
    }
}
