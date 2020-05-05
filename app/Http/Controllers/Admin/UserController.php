<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Chat;
use App\Models\Chatthreads;

use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    use NotificationTrait;
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager', 'permission:view users']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.users.index');

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxUsers(Request $request)
    {
        // return Auth::user()
        if ((int)$request->set_session === 0) {
            $request->session()->forget('full_name_filter');
            $request->session()->forget('user_email_filter');
            $request->session()->forget('user_address_filter');
            $request->session()->forget('user_phone_filter');
            $request->session()->forget('user_is_address_verified_filter');
        }
        $users = User::with(["neighborhoods.neighborhood_detail"])
                ->where(function ($users) use ($request) {
                    if ($request->has('full_name') || $request->session()->has('full_name_filter')) {
                        //storing data in session
                        $request->session()->put('full_name_filter', $request->full_name);
                        //get custom value
                        $customValue = ($request->full_name) ? $request->full_name : $request->session()->get('full_name_filter');

                        //filtering data
                        if (!empty($customValue)) {
                            //
                            $names = explode(" ", $customValue);
                            $names = array_map('strtolower', $names);
                            $users->where(function($query) use ($customValue, $names) {
                                // $query->where('user_fname','like', "%$customValue%");
                                $query->whereIn('user_fname', $names);
                                $query->orWhere(function($query) use ($customValue, $names) {
                                    // $query->where('user_lname','like', "%$customValue%");
                                    $query->whereIn('user_lname', $names);
                                });
                            });
                        }
                    }
                    if ($request->has('user_email') || $request->session()->has('user_email_filter')) {
                        //storing data in session
                        $request->session()->put('user_email_filter', $request->user_email);

                        //get custom value
                        $customValue = ($request->user_email) ? $request->user_email : $request->session()->get('user_email_filter');

                        //filtering data
                        if (!empty($customValue)) {
                            $users->where('user_email', 'like', "%$customValue%");
                        }
                    }
                    if ($request->has('user_address') || $request->session()->has('user_address_filter')) {
                        //storing data in session
                        $request->session()->put('user_address_filter', $request->user_address);

                        //get custom value
                        $customValue = ($request->user_address) ? $request->user_address : $request->session()->get('user_address_filter');

                        //filtering data
                        if (!empty($customValue)) {
                            $users->where('user_address', 'like', "%$customValue%");
                        }
                    }
                    if ($request->has('user_phone') || $request->session()->has('user_phone_filter')) {
                        //storing data in session
                        $request->session()->put('user_phone_filter', $request->user_phone);

                        //get custom value
                        $customValue = ($request->user_phone) ? $request->user_phone : $request->session()->get('user_phone_filter');

                        //filtering data
                        if (!empty($customValue)) {
                            $users->where('user_phone', 'like', "$customValue");
                        }
                    }
                    if ($request->has('user_is_address_verified') || $request->session()->has('user_is_address_verified_filter')) {

                        //storing data in session
                        $request->session()->put('user_is_address_verified_filter', $request->user_is_address_verified);

                        //get custom value
                        $customValue = ($request->user_is_address_verified) ? $request->user_is_address_verified : $request->session()->get('user_is_address_verified_filter');

                        //filtering data
                        if ($customValue == "0" || $customValue > 0) {
                            $users->where('user_is_address_verified', '=', (int)"$customValue");
                        }
                    }
                        
                    //START ADDING FILTER ON THE BASE OF ROLE SCREEN ----------------------------------------    
                    if(!empty(Auth::user()->country_id)){
                        $users->whereHas("neighborhoods.neighborhood_detail", function($q) use($customValue){
                            $q->where("country_id", (int) Auth::user()->country_id);
                        });  
                    }   
                    if(!empty(Auth::user()->state_id)){
                        // dd(Auth::user()->state_id);
                        $users->whereHas("neighborhoods.neighborhood_detail", function($q) use($customValue){
                            $q->where("state_id", (int) Auth::user()->state_id);
                        });  
                    }   
                    if(!empty(Auth::user()->city_id)){
                        $users->whereHas("neighborhoods.neighborhood_detail", function($q) use($customValue){
                            $q->where("city_id", (int) Auth::user()->city_id);
                        });  
                    }
                    if(!empty(Auth::user()->neighborhoods)){
                        $users->whereHas("neighborhoods.neighborhood_detail", function($q) use($customValue){
                            $q->whereIn("_id", Auth::user()->neighborhoods);
                        });  
                    }
                    // END ADDING FILTER ON THE BASE OF ROLE SCREEN ------------------------------------------
                })->orderBy("created_at", "desc")->get();
            // dd($users);
        return Datatables::of($users)
            ->addColumn('full_name', function ($user) {
                return "<p class='break-words'>".$user->full_name."</p>";
            })
            ->addColumn('user_email', function ($user) {
                return $user->user_email;
            })
            ->addColumn('user_address', function ($user) {
                return "<p class='break-words'>".$user->user_address."</p>";
            })
            ->addColumn('user_phone', function ($user) {
                return $user->user_phone;
            })
            ->addColumn('user_address_status', function ($user) {
                if ($user->user_is_address_verified == 0) {
                    return '<span class="label label-info btn-circle label-sm label-sm-custom"> Pending Address </span>';
                } elseif ($user->user_is_address_verified == 1) {
                    return '<span class="label label-primary btn-circle label-sm label-sm-custom"> Waiting for Approval</span>';
                } elseif ($user->user_is_address_verified == 2) {
                    return '<span class="label label-success btn-circle label-sm label-sm-custom"> Verified Address </span>';
                }
            })
            ->addColumn('action', function ($user) {
                $action = '';
                if (Auth::user()->can('edit users')) {
                    $action .= '<a href="' . url('admin/users') . '/' . encodeId($user->_id) . '" class="btn btn-sm btn-circle btn-default btn-editable"><i class="fa fa-search"></i> View</a>';
                }
                return $action;
            })
            ->rawColumns(['user_address_status', 'action', 'full_name', 'user_address'])
            ->make(true);
    }


    public function getChatthread()
    {
        $chats = Chat::all();
        return Datatables::of($chats)
            ->addColumn('chat_from', function ($chat) {
                return $chat->chat_from_user->full_name;
            })
            ->addColumn('chat_to', function ($chat) {
                return $chat->chat_to_user->full_name;
            })
            ->addColumn('action', function ($chat) {
                    return '<a href="' . url('admin/chat/detail') . '/' . encodeId($chat->_id) . '" class="btn btn-sm btn-circle btn-default btn-editable"><i class="fa fa-search"></i> View</a>';
            })
            ->rawColumns([ 'action'])
            ->make(true);
    }
    /**
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //getChatMessages
    

    public function show($userId)
    {
        $user = User::find(decodeId($userId));
        return view('admin.users.user_detail')->with(['user' => $user]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = User::find($request->user_id);
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,user_email,'.$user->_id.',_id'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $user->user_fname = $request->first_name;
        $user->user_lname = $request->last_name;
        $user->gender_id = $this->getGenderName($request->gender);
        if ($request->has('email')) {
            $user->user_email = $request->email;
        }
        if ($request->has('user_phone')) {
            $user->user_phone = $request->user_phone;
        }
        // send notification
        $emailTemplate = EmailTemplate::where('key', 'profile_update')->first();
        $view = "user.emails.profile_update_message";
        $emailTemplate->content = str_replace(['{username}'], [$user->name], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view, $user->email, ['mailData' => $mailData], $emailTemplate->subject);
        $user->save();
        $this->adminNotification($user,Config::get('constant.updates_user_info'),'update_profile');
        Session::flash('success', 'Profile has been updated successfully.');
        return \redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAddressVerify(Request $request)
    {
        $user = User::find($request->user_id);
        $user->user_is_address_verified = 2;
        $user->save();
        // send email to user and notification
        $this->adminNotification($user,Config::get('constant.approves_document'),'document');
        $emailTemplate = EmailTemplate::where('key', 'user_address_verification')->first();
        $view = "user.emails.address_verification_template";
        $emailTemplate->content = str_replace(['{username}'], [$user->full_name], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view, $user->user_email, ['mailData' => $mailData], $emailTemplate->subject);
        Session::put('verified', 1);
        return response()->json(['status' => true, 'message' => 'User Address has been verified.']);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfile(Request $request)
    {
        $user = User::find($request->user_id);
        if (File::exists(public_path('upload/users/' . $user->user_image))) {
            File::delete(public_path('upload/users/' . $user->user_image));
            $user->user_image = "no_image.png";
            $user->save();
            // send email to user
            $emailTemplate = EmailTemplate::where('key', 'profile_removal')->first();
            $view = "user.emails.delete_user_profile_template";
            $emailTemplate->content = str_replace(['{username}'], [$user->full_name], $emailTemplate->content);
            $mailData['content'] = $emailTemplate->content;
            $this->sendMail($view, $user->user_email, ['mailData' => $mailData], $emailTemplate->subject);

            return response()->json(['status' => true, 'message' => 'User profile has been deleted.']);
        } else {
            return response()->json(['status' => false, 'message' => 'User profile does not exists.']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAddressImage(Request $request)
    {
        $user = User::find($request->user_id);
        if (File::exists(public_path('upload/addresses/' . $user->user_address_document))) {
            File::delete(public_path('upload/addresses/' . $user->user_address_document));
            $user->user_address_document = "no_image.png";
            $user->user_is_address_verified = 0;
            $user->save();
            // send email to user and notification
            $this->adminNotification($user,Config::get('constant.rejects_document'),'document');

            $emailTemplate = EmailTemplate::where('key', 'address_image_removal')->first();
            $view = "user.emails.delete_address_image_template";
            $emailTemplate->content = str_replace(['{username}'], [$user->full_name], $emailTemplate->content);
            $mailData['content'] = $emailTemplate->content;
            $this->sendMail($view, $user->user_email, ['mailData' => $mailData], $emailTemplate->subject);

            return response()->json(['status' => true, 'message' => 'Address image has been deleted.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Address image does not exists.']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $user = User::find($request->user_id);
        $view = "user.emails.user_message_template";
        $mailData['title'] = 'Message';
        $mailData['desc'] = $request->message;
        $this->sendMail($view, $user->user_email, ['mailData' => $mailData], 'Message From Admin');
        return response()->json(['status' => true, 'message' => 'Message has been sent!.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleCollapse(Request $request)
    {
        //if no session then save as colaped
        if (Session::has('bodySidebarState') || Session::has('sidebar-ulSidebarState')) {
            Session::remove('bodySidebarState');
            Session::remove('sidebar-ulSidebarState');
        } else {
            //colapse sidebar
            Session::put('bodySidebarState', 'page-sidebar-closed');
            Session::put('sidebar-ulSidebarState', 'page-sidebar-menu-closed');
        }
    }

    /**
     * Update User approve and disapprove status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateApproveStatus(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if ((int) $request->value == 1){
                $user->ustatus_id = $this->getUserStatus('approved');

                $this->adminNotification($user,Config::get('constant.approves_user_account'),'user_account');
            } elseif ((int) $request->value == 0) {
                $user->ustatus_id = $this->getUserStatus('pending');
                $this->adminNotification($user,Config::get('constant.disapproves_user_account'),'user_account');
            }elseif((int) $request->value == 2){
                $user->ustatus_id = $this->getUserStatus('deactivate');
                $this->adminNotification($user,Config::get('constant.deactivates_user_account'),'user_account');
            }
            $user->update();
            if((int) $request->value === 0){
                $message = "User has been disapproved";
            }else if((int) $request->value === 1){
                $message = "User has been approved";
            }else{
                $message = "User has been deactivated";
            }
            return response()->json(["status" => true, "message" => $message] ,200);
        }catch(\Exception $e){
            return response()->json(["status" => true, "message" => $e->getMessage()] ,200);
        }
    }
}
