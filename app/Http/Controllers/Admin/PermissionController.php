<?php

namespace App\Http\Controllers\Admin;


use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use Maklad\Permission\Models\Permission;
use Maklad\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view permission']);
    }

    public function index()
    {
        return view('admin.permissions.index');
    }

    public function getAjaxPermissions(Request $request)
    {

        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('permission_name_filter');
        }

        $permissions = Permission::where(function($permissions) use($request){

                if ($request->has('permission_name')  || $request->session()->has('permission_name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('permission_name_filter', $request->permission_name);

                    //get custom value
                    $customValue = ($request->permission_name) ? $request->permission_name : $request->session()->get('permission_name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $permissions->orWhere('name', 'like', "%$customValue%");
                    }
                }
            })
        // ->toSql();
            ->orderBy('created_at','desc')
            ->get();

        // return $permissions;

        return DataTables::of($permissions)
            ->addColumn('name', function ($permission) {
                return $permission->name;
            })
            ->addColumn('action',function ($permission){
                $action = '';
                if (Auth::user()->can('edit permission')) {
                    $action .= '<a title="Edit permission" href="' . url('admin/permissions/' . encodeId($permission->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-edit"></i> Edit</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        Permission::create(['name'=>$request->name]);
        return \redirect('admin/permissions');
    }
    public function edit($id)
    {
        $permission = Permission::find(decodeId($id));

        return view('admin.permissions.edit',compact('permission'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $role = Permission::find($id);
        $role->name = $request->name;
        $role->update();
        return \redirect('admin/permissions');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role  = Permission::find($id);
        if ($role) {
            $role->delete();
            return response()->json(['status'=>true,'message'=>'Permission has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Permission is not deleted']);
        }


    }
}
