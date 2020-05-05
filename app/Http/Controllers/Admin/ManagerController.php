<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\EmailTemplate;
use App\Models\Neighborhood;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maklad\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ManagerController extends Controller
{

    public function __construct()
    {
        // $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view managers']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.managers.index');
    }

    public function getAjaxManagers(Request $request)
    {
        if ((int) $request->set_session === 0) {
            $request->session()->forget('name_filter');
            $request->session()->forget('email_filter');
            $request->session()->forget('role_filter');
        }
        $managers = Admin::with('roles')
            ->where(function ($roles) use($request) {
                if ($request->has('name') || $request->session()->has('name_filter')){
                    $request->session()->put('name_filter', $request->name_filter);
                    $value = ($request->name) ? $request->name : $request->session()->get('name_filter');
                    if (!empty($value)) {
                        $roles->where('name','like',"%$value%");
                    }

                }
                if ($request->has('email') || $request->session()->has('email_filter')){
                    $request->session()->put('email_filter', $request->name_filter);
                    $value = ($request->email) ? $request->email : $request->session()->get('email_filter');
                    if (!empty($value)) {
                        $roles->where('email','like',"%$value%");
                    }

                }
                if ($request->has('role') || $request->session()->has('role_filter')){
                    $request->session()->put('role_filter', $request->role_filter);
                    $value = ($request->role) ? $request->role : $request->session()->get('role_filter');
                    if (!empty($value)) {

                        $roles->where(function ($query) use ($request, $value) {
                            $query->whereHas('roles', function ($q) use ($request, $value) {
                                $q->where("name", 'like', "%$value%");
                                // $q->orWhere(DB::raw('concat(user_fname," ",user_lname)'), 'like', "%$customValue%");
                            });
                        });
                    }

                }
            })->orderBy('created_at', 'desc')
            ->get();




        //$managers = Admin::with('roles')->get();
        return DataTables::of($managers)
            ->addColumn('name', function ($manager) {
                return $manager->name;
            })
            ->addColumn('email', function ($manager) {
                return $manager->email;
            })
            ->addColumn('role', function ($manager) {
                $roleName =  $manager->roles->map(function($role){
                    return  $role->name;
                });
                return trim($roleName,'[" "]');
            })
            ->addColumn('action',function ($manager){
                $action = '';
                if (Auth::user()->can('edit managers')) {
                    $action .= '<a title="Edit Manger" href="' . url('admin/managers/' . encodeId($manager->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-edit"></i> Edit</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create()
    {
        $roles = Role::all()->pluck('name','_id');
        $countries = app("App\Http\Controllers\Admin\LocationDropDownController")->getCountries();
        $neighborhoods = Neighborhood::all();
        return view('admin.managers.create')->with(["roles" => $roles, "countries" => $countries, "neighborhoods" => $neighborhoods]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'role'      => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        // return $request->all();
        $managerPassword  = generatePIN(8);
        $roles = explode('_',$request->role);

        $manager = new Admin();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->password = bcrypt($managerPassword);
        $manager->role_id = $roles[0];
        $manager->country_id    = ($request->has("country")) ? $request->country : null;
        $manager->state_id      = ($request->has("state")) ? $request->state : null;
        $manager->city_id       = ($request->has("city")) ? $request->city : null;
        $manager->neighborhoods = ($request->has("neighborhoods")) ? $request->neighborhoods : null;
        $manager->save();
        $manager->assignRole($roles[1]);
        // email send to user
        $emailTemplate = EmailTemplate::where('key', 'admin_account_create')->first();
        $view = "user.emails.neighborhood_account_creation_template";
        $emailTemplate->content = str_replace(['{username}','{useremail}','{userpassword}'], [$manager->name,$manager->email,$managerPassword], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view, $manager->email, ['mailData' => $mailData], $emailTemplate->subject);

        Session::flash('success', 'Manager added successfuly.');
        return \redirect('admin/managers');
    }
    public function edit($id)
    {
        $states = []; $cities = [];
        $manager = Admin::find(decodeId($id));
        $selectedRole  = $manager->roles[0]->name;
        $roles = Role::all()->pluck('name','_id')->prepend('Select roles','');
        $countries  = app("App\Http\Controllers\Admin\LocationDropDownController")->getCountries();
        if (!empty($manager->country_id)) {
            $states     = State::where("country_id", (string) $manager->country_id)->get();
        }
        if (!empty($manager->state_id)) {
            $cities =  City::where("state_id", (string) $manager->state_id)->get();
        }
        $neighborhoods = Neighborhood::all();


        return view('admin.managers.edit',compact('manager','roles','selectedRole', 'countries', 'states', 'cities', 'neighborhoods'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $manager = Admin::find($id);
        $roles = explode('_',$request->role);
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->role = $roles[1];
        $manager->country_id    = ($request->has("country") && $request->filled("country")) ? $request->country : null;
        $manager->state_id      = ($request->has("state") && $request->filled("state")) ? $request->state : null;
        $manager->city_id       = ($request->has("city") && $request->filled("city")) ? $request->city : null;
        $manager->neighborhoods = ($request->has("neighborhoods") && $request->filled("neighborhoods")) ? $request->neighborhoods : null;
        $manager->update();
        $manager->syncRoles($roles[1]);
        Session::flash('success', 'Manager updated successfuly.');
        Session::flash("success", "Manager settings updated successfuly.");
        return \redirect("admin/managers");
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role  = Admin::find($id);
        if ($role) {
            $role->delete();
            return response()->json(['status'=>true,'message'=>'managers has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'managers is not deleted']);
        }


    }
}
