<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\Facades\DataTables;
use App\Models\AppNotification;
use Session;

class AppNotificationController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['role:Super Admin|Neighborhood Manager|Area Manager','permission:view classified categories|edit classified category|delete classified category|view classified category']);
    }
    public function index()
    {
        return view('admin.app_notifications.index');
    }
    public function getAjaxAppNotifications(Request $request)
    {
        if ((int)$request->set_session === 0) {
            $request->session()->forget('app_notification_name_filter');
        }
        $appNotifications = AppNotification::where(function($appNotification) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('app_notification_name') || $request->session()->has('app_notification_name_filter')) {
                //storing data in session
                $request->session()->put('app_notification_name_filter', $request->app_notification_name);
                //get custom value
                $customValue = ($request->app_notification_name) ? $request->app_notification_name : $request->session()->get('app_notification_name_filter');
                //filtering data
                if (!empty($customValue)) {
                    $appNotification->where('name', 'like', "%$customValue%");
                }
            }
        })->orderBy("created_at", "desc")->get();

        return DataTables::of($appNotifications)
            ->addColumn('name', function($notification){
                return ucwords($notification->name);
            })
            ->addColumn('message', function($notification){
                return ucwords($notification->message);
            })
            ->addColumn('action', function($notification){

                   return '<a href="' . url('admin/app_notification/edit/' . encodeId($notification->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
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

        $appNotification = AppNotification::where("_id", decodeId($apNotificationId))->first();

        return view("admin.app_notifications.edit", compact("appNotification"));
    }

    /**
     * Update Aps notification
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id){

        $request->validate([
            'name' => 'required',
            'message' => 'required'
        ]);
         AppNotification::where("_id", $id)->update(["name" => $request->name,'message'=> $request->message]);
        Session::flash("success", "Notification updated successfully.");

        return redirect("admin/app_notification");
    }

}
