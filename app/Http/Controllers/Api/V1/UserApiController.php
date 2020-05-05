<?php

namespace App\Http\Controllers\Api\V1;


use App\Jobs\SendEmail;
use App\Models\AppSetting;
use App\Models\Device;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\UserApplicationSetting;
use App\Models\UserSetting;
use App\Models\UserStatus;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UserApiController extends Controller
{
    use ApiResponse, NotificationTrait;

    /**
     * Update the User Profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_fname' => 'required',
                'user_lname' => 'required',
                'user_image' => '',
                'user_address' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            if (isset($request->user_image)) {
                $image = $request->file('user_image');
                $input['image'] = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('upload/users');
                $image->move($destinationPath, $input['image']);
                $inputData['user_image'] = $input['image'];
            }
            if (isset($request->user_password) && !is_null($request->user_password)) {
                $inputData['user_password'] = bcrypt($request->user_password);
            }
            if (isset($request->user_phone) && !is_null($request->user_phone)) {
                $inputData['user_phone'] = $request->user_phone;
            }
            if (isset($request->user_date_of_birth) && !is_null($request->user_date_of_birth)) {
                $inputData['user_date_of_birth'] = date('Y-m-d', strtotime($request->user_date_of_birth));
            }
            $inputData['user_fname'] = $request->user_fname;
            $inputData['user_lname'] = $request->user_lname;
            $inputData['user_address'] = $request->user_address;
            User::where('_id', Auth::id())->update($inputData);
            $user = User::find(Auth::id());
            $user->total_joined_neighborhoods = count(Auth::user()->neighborhoods);
            if (count($user->neighborhoods) > 0) {
                $user->neighborhood = $user->neighborhoods[0]->neighborhood_detail;
            } else {
                $user->neighborhood = (object)[];
            }
            $message = Lang::get('api.update_profile_message');
            return $this->sucessResponse(true, $message, $user);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Reset user password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswords(Request $request)
    {
//        try {
            $validator = Validator::make($request->all(), [
                'user_email' => 'required|exists:users,user_email',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            Log::info("Request Cycle with Queues Begins");
            $user = User::where('user_email', $request->user_email)->first();
            //$this->getPostNotificationMessage();
            dispatch(new SendEmail($user))->delay(now()->addSecond(1));
            //dd($result);
            Log::info("Request Cycle with Queues Ends");

            $message = Lang::get('api.reset_password_message');
            return $this->sucessResponse(true, $message, []);
       /* } catch (\Exception $e) { dd($e->getMessage());
            $message = Lang::get('api.email_not_sent_message');
            return response()->json(['message' => $message]);
        }*/

    }


    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_email' => 'required|exists:users,user_email',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }

            $user = User::where('user_email', $request->user_email)->first();

            $userData['id'] = $user->_id;
            $userData['user_email'] = $user->user_email;
            $userData['user_fname'] = $userData;
            $userDataEncode = base64_encode(json_encode($userData));
            $user->remember_token = $userDataEncode;
            $user->save();
            // send reset password email
            $emailTemplate = EmailTemplate::where('key', 'reset_password')->first();
            $userLink = url('user/password/reset/' . $userDataEncode);
            $view = "user.emails.reset_password_template";
            $emailTemplate->content = str_replace(['{email_link}'], [$userLink], $emailTemplate->content);
            $mailData['content'] = $emailTemplate->content;
            $this->sendMail($view, $request->user_email, ['mailData' => $mailData], $emailTemplate->subject);
            $message = Lang::get('api.reset_password_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            $message = Lang::get('api.email_not_sent_message');
            return response()->json(['message' => $message]);
        }

    }

    /**
     * Update the user App Setting
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAppSetting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'setting_id' => 'required|json'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            $settingkeys = json_decode($request->setting_id);
            UserSetting::where('user_id', Auth::id())->delete();
            $settingArr = [];
            if (count($settingkeys) > 0) {
                foreach ($settingkeys as $settingId) {
                    $settingData['user_id'] = Auth::id();
                    $settingData['app_setting_id'] = $settingId;
                    $settingArr[] = $settingData;
                }
                UserSetting::insert($settingArr);
            }
            $message = Lang::get('api.update_setting_mesage');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Get list of user Post setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function appSettingList()
    {
        $userSetting = UserSetting::where('user_id', Auth::id())->get();
        $appSetting = AppSetting::all();
        $settings = $appSetting->map(function ($setting) use ($userSetting) {
            $setting['is_setting_on'] = $userSetting->contains('app_setting_id', $setting->_id);
            return $setting;
        });
        $message = Lang::get('api.setting_list_message');
        return $this->sucessResponse(true, $message, $settings);
    }

    /**
     * Get list of User application setting max videos,image,post and message length
     * @return \Illuminate\Http\JsonResponse
     */
    public function userApplicationSettingList()
    {
        $userAppSetting = UserApplicationSetting::all();
        $message = Lang::get('api.user_app_setting_message');
        return $this->sucessResponse(true, $message, $userAppSetting);
    }


    public function saveDeviceStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'token' => 'required',
            'type' => 'required',
            'app_mode' => 'required',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $device = Device::where(['device_id'=>$request->device_id,'user_id'=>Auth::id()])->first();
        if ($device) {
            Device::where(['device_id'=>$request->device_id,'user_id'=>Auth::id()])->delete();
        }
        $device = new Device();
        $device->device_id = $request->device_id;
        $device->token = $request->token;
        $device->type = $request->type;
        $device->app_mode = $request->app_mode;
        $device->user_id = Auth::id();
        $device->save();
        $message = Lang::get('api.device_status_message');
        return $this->sucessResponse(true, $message, []);

    }

    /**
     * Get user detail
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetail($userId)
    {
        if (!$user = User::find($userId)){
            $message = Lang::get('api.user_not_found');
            return $this->sucessResponse(false,$message,[]);
        }
        $user =  User::find($userId);
        $message = Lang::get('api.user_detail_message');
        return $this->sucessResponse(true, $message, $user);
    }
    /**
     * Get Deactiavte account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivateAccount(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,device_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        try{
            
            $user_status = UserStatus::where('ustatus_name','deactivate')->first();
            User::where('_id', Auth::id())->update(array('ustatus_id'=>$user_status->id));

            $device = Device::where('device_id',$request->device_id)->first();
            if ($device) {
                Device::where('device_id',$request->device_id)->delete();
            }
            $message = Lang::get('api.account_deactivate_message');
            return $this->sucessResponse(true, $message,[]);

        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }
}
