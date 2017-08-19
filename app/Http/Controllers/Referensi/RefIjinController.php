<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\RefIjin;
use Yajra\Datatables\Datatables;

class RefIjinController extends Controller
{
  private $rules = [
    'name'  => 'required',
    'symbol' => 'required'
  ];

  public function index()
  {
    return view('referensi.ijin_list');
  }

  public function create()
  {
    return view('referensi.ijin_form');
  }

  public function edit($id)
  {
    $ref_ijin = RefIjin::find($id);

    return view('referensi.ijin_form')->withData($ref_ijin);
  }

  public function store(Request $request)
  {
    $this->validate($request, $this->rules);

    RefIjin::create([
      'name' => $request->name,
      'symbol' => $request->symbol
    ]);

    return redirect('ref_ijin_list')->with('success','Data berhasil disimpan!');
  }

  public function update(Request $request)
  {
    $this->validate($request, $this->rules);

    $ref_ijin = RefIjin::find($request->id);

    $ref_ijin->update([
      'name' => $request->name,
      'symbol' => $request->symbol
    ]);

    return redirect('ref_ijin_list')->with('success','Data berhasil disimpan!');
  }

  public function apiListRefIjin()
  {
    $data = RefIjin::orderBy('name','asc');

    return Datatables::of($data)
    ->make(true);
  }

  public function apiDeleteRefIjin(Request $request)
  {
    $data = $request->input('data');

    foreach ($data as $id) {
      $status = RefIjin::find($id)->forceDelete();
    }

    return response()->json($status);
  }

}
