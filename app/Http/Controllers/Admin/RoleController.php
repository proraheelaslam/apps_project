<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Maklad\Permission\Models\Permission;
use Maklad\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view roles|edit roles']);
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function getAjaxRoles(Request $request)
    {
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('name_filter');
        }

        $roles = Role::where(function($roles) use($request){

                if ($request->has('name')  || $request->session()->has('name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('name_filter', $request->name);

                    //get custom value
                    $customValue = ($request->name) ? $request->name : $request->session()->get('name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $roles->where('name', 'like', "%$customValue%");
                    }
                }
            })
            ->orderBy('created_at','desc')
            ->get();

        return DataTables::of($roles)
            ->addColumn('name', function ($role) {
                return $role->name;
            })
            ->addColumn('action',function ($role){
                $action = '';
                if (Auth::user()->can('edit roles')) {
                    $action .= '<a title="Edit Role" href="' . url('admin/roles/' . encodeId($role->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"> <i class="fa fa-edit"></i> Edit</a>';
                }
                if (Auth::user()->can('edit role_permission')) {
                    $action .= '<a title="Role Permission" href="' . url('admin/roles/get-permissions/' . encodeId($role->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"> <i class="fa fa-key"></i> Role Permission</a>';
                }
                return $action;
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        Role::create(['name'=>$request->name]);
        return \redirect('admin/roles');
    }
    public function edit($id)
    {
        $role = Role::find(decodeId($id));
        return view('admin.roles.edit',compact('role'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->update();
        Session::flash("success", "Role updated successfuly.");
        return \redirect("admin/roles");
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role  = Role::find($id);
        if ($role) {
            $role->delete();
            return response()->json(['status'=>true,'message'=>'Role has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Role is not deleted']);
        }

    }

    public function getRolePermission($id)
    {
        $role = Role::find(decodeId($id));
        $permissions = Permission::all();
        return view('admin.roles.role_permission',compact('role','permissions'));

    }

    public function updateRolePermission($id, Request $request)
    {
        $role = Role::find(decodeId($id));
        foreach ($role->permissions as $single) {
            $role->revokePermissionTo($single);
        }
        $permissions = $request->permissions;
        $role->unset('permission_ids');
        if ($permissions) {
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
        Session::flash('success','Role Permission succesfuly updated');
        return \redirect('admin/roles');
    }
}
