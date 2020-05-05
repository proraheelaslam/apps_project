<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\Facades\DataTables;

use App\Models\UserApplicationSetting;
use Session;

class ApplicationSettingController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['role:Super Admin|Neighborhood Manager|Area Manager','permission:view classified categories|edit classified category|delete classified category|view classified category']);
    }
    public function index(){
        return view('admin.app_settings.index');
    }
    public function getAjaxAppSetting(Request $request)
    {
        if ((int)$request->set_session === 0) {
            $request->session()->forget('app_setting_name_filter');
        }
        $appSettings = UserApplicationSetting::where(function($appSetting) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('app_setting_name') || $request->session()->has('app_setting_name_filter')) {
                //storing data in session
                $request->session()->put('app_setting_name_filter', $request->app_setting_name);
                //get custom value
                $customValue = ($request->app_setting_name) ? $request->app_setting_name : $request->session()->get('app_setting_name_filter');
                //filtering data
                if (!empty($customValue)) {
                    $appSetting->where('setting_name', 'like', "%$customValue%");
                }
            }
        })->orderBy("created_at", "desc")->get();
        return DataTables::of($appSettings)
            ->addColumn('name', function($appSetting){
                return ucwords($appSetting->setting_name);
            })
            ->addColumn('setting_key', function($appSetting){
                return $appSetting->setting_key;
            })
            ->addColumn('setting_value', function($appSetting){
                return $appSetting->setting_value;
            })
            ->addColumn('action', function($appSetting){

                return '<a href="' . url('admin/app_settings/edit/' . encodeId($appSetting->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * View application form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.app_settings.add');
    }

    /**
     * Save application setting
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'setting_name' 	=> 'required|max:150',
            'setting_key' 	=> 'required|max:100',
            'setting_value' 	=> 'required|max:100'
        ]);
        UserApplicationSetting::create($request->all());
        Session::flash("success", "App setting has been created successfuly.");
        return redirect("admin/app_settings");

    }
    /**
     * Edit Application setting
     * @param $apNotificationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($apNotificationId){

        $appSetting = UserApplicationSetting::where("_id", decodeId($apNotificationId))->first();

        return view("admin.app_settings.edit", compact("appSetting"));
    }

    /**
     * Update Application Setting
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id){

        $request->validate([
            'setting_name'             => 'required',
            'setting_value'             => 'required'
        ]);
        UserApplicationSetting::where("_id", $id)->update(["setting_name" => $request->setting_name, 'setting_value'=>$request->setting_value]);
        Session::flash("success", "App Setting updated successfully.");
        return redirect("admin/app_settings");
    }

}
