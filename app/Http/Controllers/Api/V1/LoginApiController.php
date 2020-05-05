<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AppSetting;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;


class LoginApiController extends Controller
{

    use ApiResponse;

    /**
     * Check the user email that exist or not in users collection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,user_email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse(false, $validator, $data = []);
        }
        $message = Lang::get('api.email_verify_message');
        return $this->sucessResponse(true, $message, []);
    }

    /**
     * Register the user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,user_email',
                'password' => 'required',
                'gender' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            $inputFields = $request->all();
            $user = new User();
            $user->user_fname = $inputFields['first_name'];
            $user->user_lname = $inputFields['last_name'];
            $user->user_email = $inputFields['email'];
            $user->user_password = bcrypt($inputFields['password']);
            $user->gender_id = $this->getGenderName($inputFields['gender']);
            $user->ustatus_id = $this->getUserStatus('approved');
            $user->user_address_latitude = 0;
            $user->user_address_longitude = 0;
            $user->user_address_verify_code = 0;
            $user->user_is_address_verified = 0;
            $user->user_address_type = '';
            $user->user_date_of_birth = '';
            $user->user_image = 'no_image.png';
            $user->save();
            // update user setting
            $appSetting = AppSetting::all();
            UserSetting::where('user_id', $user->_id)->delete();
            $settingArr = [];
            if ($appSetting) {
                foreach ($appSetting as $setting) {
                    $settingData['user_id'] = $user->_id;
                    $settingData['app_setting_id'] = $setting->_id;
                    $settingArr[] = $settingData;
                }
                UserSetting::insert($settingArr);
            }
            $message = Lang::get('api.register_message');
            $response['token'] = $user->createToken('nextneighbour')->accessToken;
            $userObj = new \stdClass();
            $userObj->gender = $user->gender->gender_name;
            $userObj->status = $user->status->ustatus_name;
            if (count($user->neighborhoods) > 0) {
                $user->neighborhood = $user->neighborhoods[0]->neighborhood_detail;
            } else {
                $user->neighborhood = (object)[];
            }
            $user->total_joined_neighborhoods = count($user->neighborhoods);
            $response['user'] = $user;
            // send register email
            $emailTemplate = EmailTemplate::where('key', 'register_email')->first();
            $emailTemplate->content = str_replace(['{user_name}'], [$user->full_name], $emailTemplate->content);
            $view = "user.emails.register_email_template";
            $mailData['title'] = $emailTemplate->subject;
            $mailData['desc'] = $emailTemplate->content;
            $this->sendMail($view, $user->user_email, ['mailData' => $mailData], $emailTemplate->subject);
            return $this->sucessResponse(true, $message, $response);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Login user by email and password
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            if (Auth::attempt(['user_email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                if ($user->status->ustatus_name == 'approved') {
                    $user->user_last_login = Carbon::now()->toDateTimeString();
                    $user->user_ip_address = $request->ip();
                    $user->save();
                    $message = Lang::get('api.login_message');
                    $response['token'] = $user->createToken('nextneighbour')->accessToken;
                    $response['total_unread_notifications'] = UserNotification::where('to_user_id', Auth::id())->where('is_seen',0)->count();
                    $userObj = new \stdClass();

                    $userObj->gender = $user->gender->gender_name;
                    $userObj->status = $user->status->ustatus_name;
                    $user->user_address_latitude = $user->user_address_latitude;
                    $user->user_address_longitude = $user->user_address_longitude;
                    $user->user_address_verify_code = $user->user_address_verify_code;
                    $user->user_is_address_verified = $user->user_is_address_verified;
                    $user->user_address_type = $user->user_address_type;
                    $user->total_joined_neighborhoods = count(Auth::user()->neighborhoods);
                    if (count($user->neighborhoods) > 0) {
                        $user->neighborhood = $user->neighborhoods[0]->neighborhood_detail;
                    } else {
                        $user->neighborhood = (object)[];
                    }
                    $response['user'] = $user;
                    return $this->sucessResponse(true, $message, $response);
                }else if($user->status->ustatus_name == 'deactivate'){

                    $message = Lang::get('api.deactivate_message');
                    return $this->errorResponse(false, $message, []);
                    
                }else {
                    $message = Lang::get('api.account_block_message');
                    return $this->errorResponse(false, $message, []);
                }

            } else {
                $message = Lang::get('api.login_error_message');
                return $this->errorResponse(false, $message, []);

            }
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Verify User Address by both Document or Code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyAddress(Request $request)
    {
        try {
            $message = "";
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'type' => 'in:document,code'
            ]);

            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }
            if ($request->type == 'document') {
                $validator = Validator::make($request->all(), [
                    'address_document' => 'required|image|mimes:jpeg,png,jpg',
                ]);
                if ($validator->fails()) {
                    return $this->validationResponse(false, $validator, $data = []);
                }
                $image = $request->file('address_document');
                $input['document_name'] = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('upload/addresses');
                $image->move($destinationPath, $input['document_name']);
                $user->user_address_document = $input['document_name'];
                $message = Lang::get('api.address_verify_message');

            } elseif ($request->type == "code") {
                $user->user_address_verify_code = generatePIN(4);
                $message = "Address code has been verified we send message in 24 hours";
            }

            $user->user_address = $request->address;
            $user->user_address_latitude = $request->latitude;
            $user->user_address_longitude = $request->longitude;
            $user->user_address_type = $request->type;
            $user->user_is_address_verified = 1;
            $user->save();
            $user->total_joined_neighborhoods = count(Auth::user()->neighborhoods);
            if (count($user->neighborhoods) > 0) {
                $user->neighborhood = $user->neighborhoods[0]->neighborhood_detail;
            } else {
                $user->neighborhood = (object)[];
            }
            return $this->sucessResponse(true, $message, $user);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }

    }

    /**
     * Verify User Pin Code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function codeVerify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $setting = $this->getAddressPinCode(Auth::id(), $request->code);
            if (!is_null($setting)) {
                $user = Auth::user();
                $user->user_is_address_verified = 2;
                $user->save();
                $user->total_joined_neighborhoods = count(Auth::user()->neighborhoods);
                if (count($user->neighborhoods) > 0) {
                    $user->neighborhood = $user->neighborhoods[0]->neighborhood_detail;
                } else {
                    $user->neighborhood = (object)[];
                }
                $message = Lang::get('api.code_verify_message');
                return $this->sucessResponse(true, $message, $user);
            } else {
                $message = Lang::get('api.code_not_verify_message');
                return $this->errorResponse(false, $message, []);
            }
        }catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

}
