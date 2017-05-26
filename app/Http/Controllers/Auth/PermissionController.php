<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use Yajra\Datatables\Datatables;

class PermissionController extends Controller
{
    public function index()
    {
      return view('auth.permission_list');
    }

    public function showPermission()
    {
      return view('auth.permission');
    }

    public function create(Request $request)
    {
      $data = $request->all();

      $this->validate($request, [
          'name' => 'required|unique:roles|max:255',
          'display_name' => 'required'
      ]);

      $permission = Permission::create($request->except(['_token','id']));

      return redirect('permission_list')->with('success','Data berhasil disimpan!');
    }

    public function update(Request $request)
    {
      $data = $request->all();

      $this->validate($request, [
          'name' => 'required|unique:roles|max:255',
          'display_name' => 'required'
      ]);

      $permission = Permission::find($data['id']);
      $permission->update($request->except(['_token','id']));

      return view('auth.permission_list')->withData($permission)->with('success','Data berhasil disimpan!');
    }

    public function showPermissionUpdate($id)
    {
      $permission = Permission::find($id);

      return view('auth.permission')->withData($permission);
    }

    public function apiDeletePermission(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = Permission::where('id',$id)->delete();
      }

      return response()->json($status);
    }

    public function apiGetPermission()
    {
      return Datatables::of(Permission::all())->make(true);
    }
}
