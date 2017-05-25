<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Model\Permission;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    public function index()
    {
      return view('auth.role_list');
    }

    public function showRole()
    {
      $permission = Permission::pluck('display_name','id');

      return view('auth.role')->with('permission',$permission);
    }

    public function showUpdate($id)
    {
      $permission = Permission::pluck('display_name','id');
      $role = Role::find($id);

      return view('auth.role')->withData($role)->withPermission($permission);
    }

    public function create(Request $request)
    {
      $data = $request->all();

      $this->validate($request, [
          'name' => 'required|unique:roles|max:255',
          'display_name' => 'required',
          'permission' => 'required|array|min:1'
      ]);

      $role = Role::create($request->except(['_token','permission','id']));

      foreach ($data['permission'] as $key => $value) {
        $role->attachPermission($value);
      }

      return redirect('role_list')->with('success','Data berhasil disimpan!');
    }

    public function update(Request $request)
    {
      $this->validate($request, [
          'name' => 'required|unique:roles|max:255',
          'display_name' => 'required',
          'permission' => 'required|array|min:1'
      ]);

      $role = Role::find($request->id);

      $role->update($request->except(['_token','permission','id']));

      $role->perms()->detach();

      foreach ($request->permission as $key => $value) {
        $role->attachPermission($value);
      }

      return redirect('role_list')->with('success','Data berhasil diupdate!');
    }

    public function apiDeleteRole(Request $request)
    {
      $data = $request->input('data');

      foreach ($data as $id) {
        $status = Role::where('id',$id)->delete();
      }

      return response()->json($status);
    }

    public function apiGetRole()
    {
      return Datatables::of(Role::with('perms'))->make(true);
    }
}
