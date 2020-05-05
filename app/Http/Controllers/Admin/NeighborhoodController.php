<?php

namespace App\Http\Controllers\Admin;

use App\Models\Neighborhood;
use App\Models\User;
use App\Models\UserNeighborhood;
use App\Models\EmailTemplate;
use App\Models\State;
use App\Models\City;

use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class NeighborhoodController extends Controller
{

    use NotificationTrait;
    public function __construct()
    {
//        ini_set('memory_limit', '-1');
        $this->middleware(['role:Neighborhood Manager|Super Admin|Area Manager','permission:view neighborhoods|view export_neighborhood']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.neighborhoods.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxNeighborhoods(Request $request)
    {
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('neighborhood_name_filter');
            $request->session()->forget('neighborhood_address_filter');
            $request->session()->forget('neighborhood_total_users_filter');
            $request->session()->forget('verified_by_admin_filter');
        }

        $neighborhoods = Neighborhood::where(function($neighborhoods) use($request){
                //START ADDING FILTER ON THE BASE OF ROLE SCREEN ----------------------------------------

                    if(!empty(Auth::user()->country_id)){
                        $neighborhoods->where("country_id", (int) Auth::user()->country_id);  
                    }
                    if(!empty(Auth::user()->state_id)){
                        $neighborhoods->where("state_id", (int) Auth::user()->state_id);  
                    }
                    if(!empty(Auth::user()->city_id)){
                        $neighborhoods->where("city_id", (int) Auth::user()->city_id);  
                    }
                    if(!empty(Auth::user()->neighborhoods)){
                        $neighborhoods->whereIn("_id", Auth::user()->neighborhoods);  
                    }
                    
                // END ADDING FILTER ON THE BASE OF ROLE SCREEN ------------------------------------------

                if ($request->has('neighborhood_name')  || $request->session()->has('neighborhood_name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('neighborhood_name_filter', $request->neighborhood_name);

                    //get custom value
                    $customValue = ($request->neighborhood_name) ? $request->neighborhood_name : $request->session()->get('neighborhood_name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $neighborhoods->where('neighborhood_name', 'like', "%$customValue%");
                    }
                }


                if ($request->has('neighborhood_address')  || $request->session()->has('neighborhood_address_filter'))
                {
                    //storing data in session
                    $request->session()->put('neighborhood_address_filter', $request->neighborhood_address);

                    //get custom value
                    $customValue = ($request->neighborhood_address) ? $request->neighborhood_address : $request->session()->get('neighborhood_address_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $neighborhoods->where('neighborhood_address', 'like', "%$customValue%");
                    }
                }
                if ($request->has('neighborhood_total_users')  || $request->session()->has('neighborhood_total_users_filter'))
                {
                    //storing data in session
                    $request->session()->put('neighborhood_total_users_filter', $request->neighborhood_total_users);

                    //get custom value
                    $customValue = ($request->neighborhood_total_users) ? $request->neighborhood_total_users : $request->session()->get('neighborhood_total_users_filter');
                    
                    //filtering data
                    if (isset($customValue)) {
                        $customValue = (int) $customValue;
                       $neighborhoods->where('neighborhood_total_users', '>=' ,$customValue);
                    }
                }
                if ($request->has('verified_by_admin')  || $request->session()->has('verified_by_admin_filter'))
                {

                    //storing data in session
                    $request->session()->put('verified_by_admin_filter', $request->verified_by_admin);

                    //get custom value
                    $customValue = ($request->verified_by_admin) ? $request->verified_by_admin : $request->session()->get('verified_by_admin_filter');

                    //filtering data
                    if ($customValue == "0" || $customValue > 0) {
                       $neighborhoods->where('verified_by_admin', '=', (int) "$customValue");
                    }
                }
        })
            ->orderBy('neighborhood_created_at','desc')
            // ->toSql();
            ->get();

        return DataTables::of($neighborhoods)
            ->addColumn('neighborhood_name', function ($neighborhood) {
                return "<p class='text-left'> $neighborhood->neighborhood_name </p>";
            })
            ->addColumn('neighborhood_address', function ($neighborhood) {
                return "<p class='break-words'>".$neighborhood->neighborhood_address."</p>";
            })
            ->addColumn('neighborhood_total_users', function ($neighborhood) {
                // return $neighborhood->neighborhood_total_users;
                return '<a href="'.url('admin/neighborhoods/'.$neighborhood->_id.'/users').'" >'.$neighborhood->neighborhood_total_users.'</a>';
            })
            ->addColumn('neighbourhood_is_verify', function ($neighborhood) {
                if($neighborhood->verified_by_admin == 0) {
                    return '<span class="label label-info btn-circle label-sm label-sm-custom"> Pending </span>';
                } elseif ($neighborhood->verified_by_admin == 1){
                    return '<span class="label label-success btn-circle label-sm label-sm-custom"> Verified </span>';
                }
            })
            ->addColumn('action', function ($neighborhood) {
                $action = '';
                if (Auth::user()->can('view neighborhoods')) {
                    $action .= '<a href="' . url('admin/neighborhoods') . '/' . encodeId($neighborhood->_id) . '" class="btn btn-sm btn-circle btn-default btn-editable"><i class="fa fa-search"></i> View</a>';
                }
                return $action;
             })
            ->rawColumns(['neighborhood_name', "neighborhood_address", 'neighborhood_total_users','neighbourhood_is_verify','action', ''])
            
            ->make(true);
    }

    /**
     * @param $neighborhoodId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($neighborhoodId)
    {
        $states = []; $cities = [];
        $neighborhoods = Neighborhood::find(decodeId($neighborhoodId));
        $countries  = app("App\Http\Controllers\Admin\LocationDropDownController")->getCountries();

        if (!empty($neighborhoods->country_id)) {
            $states     = State::where("country_id", (string) $neighborhoods->country_id)->get();
        }
        if (!empty($neighborhoods->state_id)) {
            $cities = City::where("state_id", (string)$neighborhoods->state_id)->get();
        }
        return view('admin.neighborhoods.neighborhood_detail')
                ->with([
                    'neighborhoods' =>$neighborhoods,
                    'countries'     =>$countries,
                    'states'        =>$states,
                    'cities'        =>$cities,
                ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighborhoodVerify(Request $request)
    {

        $neighborhood = Neighborhood::find($request->neighborhood_id);
        if ((int) $request->status == 0) {
            $neighborhood->verified_by_admin = 0;
            $neighborhoodKey = Config::get('constant.neighbourhood_declined');
        }else{
            $neighborhood->verified_by_admin = 1;
            $neighborhoodKey = Config::get('constant.neighbourhood_approved');
        }
        $neighborhood->save();
        $user = $neighborhood->createdBy;
        // notification
        $this->adminNotification($user,$neighborhoodKey,'neighbourhood');
        // send email to neighborhood user
        $emailTemplate = EmailTemplate::where('key', 'neighborhood_verify')->first();
        $view = "user.emails.address_verification_template";
        $emailTemplate->content = str_replace(['{username}'], [$neighborhood->createdBy->full_name], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view, $neighborhood->createdBy->user_email, ['mailData' => $mailData], $emailTemplate->subject);
        return response()->json(['status'=>true,'message'=>'Neighborhood has been verified']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function neighborhoodUsers($id)
    {
         return view('admin.neighborhoods.neighborhood_users')->with('id',$id);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxNeighborhoodUsers(Request $request)
    {
        $id = $request->id;
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('nu_full_name_filter');
            $request->session()->forget('nu_user_email_filter');
            $request->session()->forget('nu_user_address_filter');
            $request->session()->forget('nu_user_phone_filter');
            $request->session()->forget('nu_user_address_status_filter');
        }

        $users = User::whereHas('neighborhoods', function ($query) use($id) {
                $query->where('neighborhood_id',$id);
            })->where(function($users) use($request){

                if ($request->has('nu_full_name')  || $request->session()->has('nu_full_name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('nu_full_name_filter', $request->nu_full_name);

                    //get custom value
                    $customValue = ($request->nu_full_name) ? $request->nu_full_name : $request->session()->get('nu_full_name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $users->orWhere('user_fname', 'like', "%$customValue%");
                        $users->orWhere('user_lname', 'like', "%$customValue%");
                    }
                }


                if ($request->has('nu_user_email')  || $request->session()->has('nu_user_email_filter'))
                {
                    //storing data in session
                    $request->session()->put('nu_user_email_filter', $request->nu_user_email);

                    //get custom value
                    $customValue = ($request->nu_user_email) ? $request->nu_user_email : $request->session()->get('nu_user_email_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $users->orWhere('user_email', 'like', "%$customValue%");
                    }
                }
                if ($request->has('nu_user_address')  || $request->session()->has('nu_user_address_filter'))
                {
                    //storing data in session
                    $request->session()->put('nu_user_address_filter', $request->nu_user_address);

                    //get custom value
                    $customValue = ($request->nu_user_address) ? $request->nu_user_address : $request->session()->get('nu_user_address_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $users->orWhere('user_address', 'like', "%$customValue%");
                    }
                }
                if ($request->has('nu_user_phone')  || $request->session()->has('nu_user_phone_filter'))
                {
                    //storing data in session
                    $request->session()->put('nu_user_phone_filter', $request->nu_user_phone);

                    //get custom value
                    $customValue = ($request->nu_user_phone) ? $request->nu_user_phone : $request->session()->get('nu_user_phone_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $users->orWhere('user_phone', 'like', (int) "$customValue");
                    }
                }
                if ($request->has('nu_user_address_status')  || $request->session()->has('nu_user_address_status_filter'))
                {

                    //storing data in session
                    $request->session()->put('nu_user_address_status_filter', $request->nu_user_address_status);

                    //get custom value
                    $customValue = ($request->nu_user_address_status) ? $request->nu_user_address_status : $request->session()->get('nu_user_address_status_filter');

                    //filtering data
                    if ($customValue == "0" || $customValue > 0) {
                       $users->orWhere('user_address_status', '=', (int) "$customValue");
                    }
                }

        })
            ->orderBy('created_at','desc')
            // ->toSql();
            ->get();



        return Datatables::of($users)
            ->addColumn('full_name', function ($user) {
                return $user->full_name;
            })
            ->addColumn('user_email', function ($user) {
                return $user->user_email;
            })
            ->addColumn('user_address', function ($user) {
                return $user->user_address;
            })
            ->addColumn('user_phone', function ($user) {
                return $user->user_phone;
            })
            ->addColumn('user_address_status', function ($user) {
                if($user->user_is_address_verified == 0) {
                    return '<span class="label label-info btn-circle label-sm label-sm-custom"> Pending Address </span>';
                } elseif ($user->user_is_address_verified == 1){
                    return '<span class="label label-primary btn-circle label-sm label-sm-custom"> Waiting for Approval</span>';
                }elseif ($user->user_is_address_verified == 2){
                    return '<span class="label label-success btn-circle label-sm label-sm-custom"> Verified Address </span>';
                }
            })
            ->addColumn('action', function ($user) use($id) {
                return '<a href="'.url('admin/users').'/'.encodeId($user->_id)  . '" class="btn btn-sm btn-circle btn-default btn-editable"><i class="fa fa-search"></i> View</a>

                    <a title="Remove User from neighbourhood" href="'.url("admin/neighborhoods/user/delete/{$id}/{$user->_id}").'" class="btn-delete btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-trash"></i> Delete</a>
                    ';
            })
            ->rawColumns(['user_address_status','action'])
            ->make(true);
    }

    /**
     * @param $neighborhoodId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($neighborhoodId)
    {

        $neighborhoods = Neighborhood::find(decodeId($neighborhoodId));
        // return $neighborhoods;
        $countries  = app("App\Http\Controllers\Admin\LocationDropDownController")->getCountries();
        $states     = State::where(function($query) use($neighborhoods){
                            if (!empty($neighborhoods->country_id)) {
                                $query->where("country_id", (string) $neighborhoods->country_id);
                            }
                        })->get();
        $cities     = City::where(function($query) use($neighborhoods){
                            if (!empty($neighborhoods->state_id)) {
                                $query->where("state_id", (string) $neighborhoods->state_id);
                            }
                        })->get();
        return view('admin.neighborhoods.edit_neighborhood_detail')
                ->with([
                        'neighborhoods' => $neighborhoods, 
                        'countries'     => $countries,
                        'states'        => $states,
                        'cities'        => $cities,
                    ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighborhoodUpdate(Request $request)
    {
        /*print_r($request->all()); exit();
         return $request->all();*/

        if(empty($request->name) || empty($request->address)){
            return response()->json(["status" => false, "message" => "Name and address fields are required."], 400);
        }
        $neighborhood = Neighborhood::find($request->neighborhood_id);
        $this->adminNotification($neighborhood,Config::get('constant.neighbourhood_updated'),'update_neighbourhood');

        $areaArra = $request->corrdinates;
        if (!empty($areaArra)) {
            $neighborhoodCords  = [];
            foreach ($areaArra as $area) {
                $cordsArr = [];
                $cordsArr[] = (float)$area[0];
                $cordsArr[] = (float)$area[1];
                $neighborhoodCords[] = $cordsArr;
            }
            $neighborhoodCords[] = $neighborhoodCords[0];
            
            $addressAreaFormat = [
                'coordinates' => [$neighborhoodCords],
                'type' => 'Polygon'
            ];
            $neighborhood->neighborhood_area = $addressAreaFormat;
        }
        
        $neighborhood->neighborhood_name = $request->name;
        $neighborhood->neighborhood_address = $request->address;
        $neighborhood->country_id   = ($request->has('country')) ? $request->country : null;
        $neighborhood->state_id     = ($request->has('state')) ? $request->state : null;
        $neighborhood->city_id      = ($request->has('city')) ? $request->city : null;

        $neighborhood->save();
        return response()->json([
                                    'status' => TRUE,
                                    'message' => 'Neighborhood detail has been updated.'
                                ]);
        // return response()->json(['status'=>true, 'message',], 200);
    }

    /**
     * @param $neighbourhoodId, $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNeighbourhoodUser($neighbourhoodId, $userId)
    {
        try{
        
            $record = UserNeighborhood::where("neighborhood_id", $neighbourhoodId)->where("user_id", $userId);
            $record->delete();

            $user = User::find($userId);
            $user_name = $user->full_name;
            $user_email = $user->user_email;


            $neighborhood = Neighborhood::find($neighbourhoodId);
            $neighborhood_name = $neighborhood->neighborhood_name;
            $neighborhood->neighborhood_total_users = --$neighborhood->neighborhood_total_users;
            $neighborhood->save();


            $emailTemplate = EmailTemplate::where('key','neighborhood_user_removal')->first();

            $view = "user.emails.delete_neighborhood_user_email_template";
            $emailTemplate->content = str_replace(['{user_name}'], [$user_name], $emailTemplate->content);
            $emailTemplate->content = str_replace(['{neighborhood_name}'], [$neighborhood_name], $emailTemplate->content);

            // return $emailTemplate->content;

            $mailData['content'] = $emailTemplate->content;
            $this->sendMail($view, $user_email, ['mailData' => $mailData], $emailTemplate->subject);

            return response()->json([
                                    'status' => true,
                                    'message' => "User removed from neighbourhood and email sent to him.",
                                ], 200);
        }catch(\Exception $e){
            return response()->json([
                                    'status' => false,
                                    'message' => $e->getMessage(),
                                ], 400);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createNeighborhoodExport()
    {
        return view('admin.neighborhoods.neighborhood_export');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function neighborhoodExport(Request $request)
    {
        $cordsArr = json_decode($request->cords[0],true);
        $cordsArr[] = $cordsArr[0];
        $addressAreaFormat = [
            'coordinates' => [$cordsArr],
            'type' => 'Polygon'
        ];

        $neighborhoodData = [
            'neighborhood_name' =>  $request->neighborhood_name,
            'neighborhood_address' => $request->neighborhood_address,
            'neighborhood_area' => $addressAreaFormat,

        ];
        $neighborhoodData = json_encode($neighborhoodData);
        $neighborhoodFile = time() . '_neighborhood.json';
        $destinationPath=   public_path()."/upload/json/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$neighborhoodFile,$neighborhoodData);
        Session::flush('status',true);
        return response()->download($destinationPath.$neighborhoodFile);
        return \redirect()->back();
    }
}
