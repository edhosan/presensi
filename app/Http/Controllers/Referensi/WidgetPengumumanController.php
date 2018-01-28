<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\WidgetPengumumanDataTable;

class WidgetPengumumanController extends Controller
{
    public function index()
    {
    	return view('referensi.widget_pengumuman_list');
    }
}
