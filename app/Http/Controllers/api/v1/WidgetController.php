<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Model\WidgetPengumuman;
use App\DataTables\WidgetPengumumanDataTable;

class WidgetController extends Controller
{
    public function pengumumanList(WidgetPengumumanDataTable $dataTable)
    {
  		//$data = WidgetPengumuman::orderBy('title','asc');

      	//return Datatables::of($data)
      	//->make(true);
    	return $dataTable->ajax();

    }
}
