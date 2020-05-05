<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Device;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class NotificationApiController extends Controller
{

    /**
     * Get User notification list
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationList()
    {
        $notificationList = UserNotification::where('to_user_id', Auth::id())->get();
        $notificationList->map(function ($notification) {
            $notification->is_seen = 1;
            $notification->save();
        });

        $notifications = UserNotification::with('user')
            ->where('to_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

       $message = Lang::get('api.notification_list_message');
       return $this->sucessResponse(true, $message,$notifications);
    }

    /**
     * Delete the device token Logout device
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,device_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

        $device = Device::where('device_id',$request->device_id)->first();
        $message = Lang::get('api.token_not_found_message');
        if ($device) {
            Device::where('device_id',$request->device_id)->delete();
            $message = Lang::get('api.delete_device_token_message');
        }
        return $this->sucessResponse(true, $message,$device);


    }

    /**
     * Get unread notifications
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|exists:user_notifications,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $unreadNotifications = UserNotification::where('to_user_id',Auth::id())->where('_id',$request->notification_id)->first();
        if ($unreadNotifications) {
           $notification =  UserNotification::where('to_user_id',Auth::id())->where('_id',$request->notification_id)->first();
           $notification->unread_status = 1;
           $notification->save();
        }
        $totalunReadNotification = UserNotification::where('to_user_id', Auth::id())->where('is_seen',0)->count();
        $result = ['total_unread_notifications' => $totalunReadNotification];
        $message = Lang::get('api.total_unread_notification');
        return $this->sucessResponse(true, $message,$result);


    }

}
