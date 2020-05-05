<?php

namespace App\Http\Controllers\admin;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\Facades\DataTables;
use App\Models\AppNotification;
use Session;

class AppPushNotificationController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['role:Super Admin|Neighborhood Manager|Area Manager','permission:view classified categories|edit classified category|delete classified category|view classified category']);
    }
    public function index()
    {
        return view('admin.app_push_notifications.index');
    }
    public function getAjaxPushNotification(Request $request)
    {
        if ((int)$request->set_session === 0) {
            $request->session()->forget('app_notification_name_filter');
        }
        $pushNotification = AppSetting::where(function($notification) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('push_notify_name') || $request->session()->has('push_notify_name_filter')) {
                //storing data in session
                $request->session()->put('push_notify_name_filter', $request->push_notify_name);
                //get custom value
                $customValue = ($request->app_notification_name) ? $request->app_notification_name : $request->session()->get('push_notify_name_filter');
                //filtering data
                if (!empty($customValue)) {
                    $notification->where('app_setting_name', 'like', "%$customValue%");
                }
            }
        })->orderBy("created_at", "desc")->get();

        return DataTables::of($pushNotification)
            ->addColumn('name', function($notification){
                return ucwords($notification->app_setting_name);
            })
            ->addColumn('action', function($notification){

                return '<a href="' . url('admin/app/push_notifications/edit/' . encodeId($notification->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get Edit view page
     * @param $apNotificationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($apNotificationId){

        $appNotification = AppSetting::where("_id", decodeId($apNotificationId))->first();

        return view("admin.app_push_notifications.edit", compact("appNotification"));
    }

    /**
     * Update Aps notification
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id){

        $request->validate([
            'name'             => 'required|max:150'
        ]);
        AppSetting::where("_id", $id)->update(["app_setting_name" => $request->name]);
        Session::flash("success", "Push Notification updated successfully.");
        return redirect("admin/app/push_notifications");
    }

}
