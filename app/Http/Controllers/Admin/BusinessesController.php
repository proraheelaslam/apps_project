<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Business;
use App\Models\Neighborhood;
use App\Models\BusinessImage;
use App\Models\BusinessLike;
use App\Models\BusinessRecommendations;
use App\Models\BusinessCategory;
use App\Models\BusinessReport;
use App\Models\BusinessReportReason;
use Session;

class BusinessesController extends Controller
{
	public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view businesses|edit business|view business|delete business']);
    }

    public function index(){
    	// return User::all();
    	$neighborhoods = Neighborhood::where(function($query){
            if(!empty(Auth::user()->neighborhoods)){
                $query->whereIn("_id", Auth::user()->neighborhoods);
            }
        })->orderBy("neighborhood_name", "asc")->get();
    	return view("admin.businesses.index", compact("neighborhoods"));
    }

    public function getAjaxBusiness(Request $request){    	
    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('business_name_filter');
            $request->session()->forget('business_phone_filter');
            $request->session()->forget('business_neighborhood_filter');
            $request->session()->forget('business_created_by_filter');
            $request->session()->forget('business_approved_filter');
        }
        // dd(Auth::user());
    	$businesses = Business::whereHas("neighborhoods", function($query){
                    //START ADDING FILTER ON THE BASE OF ROLE SCREEN ----------------------------------------
                    $query->select('neighborhood_name');

                    if(!empty(Auth::user()->country_id)){
                        $query->where("country_id", (int) Auth::user()->country_id);  
                    }
                    if(!empty(Auth::user()->state_id)){
                        $query->where("state_id", (int) Auth::user()->state_id);  
                    }
                    if(!empty(Auth::user()->city_id)){
                        $query->where("city_id", (int) Auth::user()->city_id);  
                    }
                    if(!empty(Auth::user()->neighborhoods)){
                        $query->whereIn("_id", Auth::user()->neighborhoods);  
                    }
                    
                    // END ADDING FILTER ON THE BASE OF ROLE SCREEN ------------------------------------------
                })
                ->with(["neighborhoods", "business_recommended", "likes", "users"])
    			->where(function($business) use($request){
    				/****************************** filter field 1 *************************************/
    				if ($request->has('business_name') || $request->session()->has('business_name_filter')) {

	                    //storing data in session
	                    $request->session()->put('business_name_filter', $request->business_name);

	                    //get custom value
	                    $customValue = ($request->business_name) ? $request->business_name : $request->session()->get('business_name_filter');
                        // $customValue = strtolower($customValue);
	                    //filtering data
	                    if (!empty($customValue)) {
                            $business->where('business_name', 'like', "%".$customValue."%");
	                    }
	                }
	                /****************************** filter field 2 *************************************/
	                if ($request->has('business_phone') || $request->session()->has('business_phone_filter')) {

	                    //storing data in session
	                    $request->session()->put('business_phone_filter', $request->business_phone);

	                    //get custom value
	                    $customValue = ($request->business_phone) ? $request->business_phone : $request->session()->get('business_phone_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
	                        $business->where('business_phone', 'like', "%$customValue%");
	                    }
	                }
	                /****************************** filter field 3 *************************************/
	                if ($request->has('business_neighborhood') || $request->session()->has('business_neighborhood_filter')) {

	                    //storing data in session
	                    $request->session()->put('business_neighborhood_filter', $request->business_neighborhood);

	                    //get custom value
	                    $customValue = ($request->business_neighborhood) ? $request->business_neighborhood : $request->session()->get('business_neighborhood_filter');

	                    //filtering data
	                    if (!empty($customValue)) {

	                    	$business->where(function($query) use($customValue){
	                    		$query->whereHas("neighborhoods", function($q) use($customValue){
	                    			$q->where(function($q1) use ($customValue) {
	                    				// dd($customValue);
	                                    $q1->where('_id', $customValue);

	                                });
	                    		});
	                    	});
	                    }
	                }
	                /****************************** filter field 4 *************************************/
	                if ($request->has('business_created_by') || $request->session()->has('business_created_by_filter')) {

	                    //storing data in session
	                    $request->session()->put('business_created_by_filter', $request->business_created_by);

	                    //get custom value
	                    $customValue = ($request->business_created_by) ? $request->business_created_by : $request->session()->get('business_created_by_filter');

	                    //filtering data
	                    if (!empty($customValue)) {

                            $names = explode(" ", $customValue);
                            $names = array_map('strtolower', $names);

                            $business->where(function ($query) use ($names, $customValue) {
                                $query->whereHas('users', function ($q) use ($names, $customValue) {
                                    $q->where(function($q1) use ($names, $customValue) {
                                        $q1->where('user_fname', 'like', "%$names[0]%");
                                        $q1->when(array_key_exists(1, $names), function($q2) use($names){
                                            $q2->orWhere('user_lname', 'like', "%$names[1]%");    
                                        });
                                    });
                                });
                            });
	                    }
	                }
	                /****************************** filter field 5 *************************************/
	                if ($request->has('business_approved') || $request->session()->has('business_approved_filter')) {

	                    //storing data in session
	                    $request->session()->put('business_approved_filter', $request->business_approved);

	                    //get custom value
	                    $customValue = ($request->business_approved) ? $request->business_approved : $request->session()->get('business_approved_filter');
	                    // return $customValue;
	                    //filtering data
	                    if (isset($customValue)) {
	                        $business->where('business_is_approved', (int) $customValue);
	                    }
	                }
    			})
			// ->toSql();
			->orderBy("created_at", "desc")->get();
			// dd($businesses);
		return DataTables::of($businesses)
    		->addColumn('business_name', function($business){
                $business_name = $business->business_name;
    			return "<p style='word-break:break-all'>$business_name</p>";
    		})
    		->addColumn('business_address', function($business){
    			$business_address = $business->business_address;
    			return "<p style='word-break:break-all'>$business_address</p>";
    		})
    		->addColumn('business_phone', function($business){
    			return $business->business_phone;
    		})
    		->addColumn('neighborhood_name', function($business){
    			return $business->neighborhoods->neighborhood_name;
    		})
    		->addColumn('created_by', function($business){
    			return $business->users->full_name;
    		})
    		->addColumn('business_is_approved', function($business){
    			$is_approved = $business->business_is_approved;
    			$class = '';
    			$status = '';
    			switch (true) {
    				case $is_approved < 1:
						$class = 'label-danger';
						$status = "Not Approved";
					break;
    				case $is_approved > 0:
						$class = 'label-success';
						$status = "Approved";
					break;
    			}
    			return '<span class="label '.$class.' btn-circle label-sm-custom"> '. $status .' </span>';
    		})
    		->addColumn('action', function($business){
    			$action = '';
    			if (Auth::user()->can('edit business')) {
    				$action .= '<a href="' . url('admin/business/edit/' . encodeId($business->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('view business')) {
    				$action .= '<a href="' . url('admin/business/' . encodeId($business->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-search"></i> View</a>';
    			}	
    			if (Auth::user()->can('delete business')) {
    				$action .= '<a href="' . url('admin/business/' . encodeId($business->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['business_is_approved', 'business_name', 'business_address', 'action'])
    		->make(true);
    }

    public function show($businessId){
    	$businessId = decodeId($businessId);
    	$businessDetail = Business::with(["business_images", "business_recommended.users", "likes.users", "users", "business_reports.business_report_reasons", "business_reports.reported_by_user"])->where("_id", $businessId)->first();
    	
    	// dd($businessDetail);
    	return view("admin.businesses.business_detail", compact("businessDetail"));
    }

    public function edit($businessId){
    	$businessId = decodeId($businessId);
    	$businessDetail = Business::with(["business_images", "business_recommended.users", "likes.users", "users", "categories"])->where("_id", $businessId)->first();
    	$categories = BusinessCategory::orderBy("name", "asc")->get();
    	// dd($categories);
    	return view("admin.businesses.edit", compact("businessDetail", "categories"));
    }

    public function update(Request $request, $businessId){

    	// return $request->all();

    	$request->validate([
		    'business_name' 	=> 'required|max:150',
		    'business_email' 	=> 'required|max:100',
		    'business_phone' 	=> 'required|max:100',
		    'business_website' 	=> 'required|max:100',
		    'category' 			=> 'required',
		    'business_address' 	=> 'required|max:300',
		    'address_latitude' 	=> 'required',
		    'address_longitude' => 'required',

		    'business_details' 		=> 'required|max:500',
		]);

    	$business = Business::find($businessId);
    	$business->business_name = $request->business_name;
    	$business->business_email = $request->business_email;
    	$business->business_phone = $request->business_phone;

    	$business->business_website = $request->business_website;
    	$business->business_address = $request->business_address;
    	$business->latitude = $request->address_latitude;
    	$business->longitude = $request->address_longitude;
    	$business->category_id = $request->category;
    	$business->business_details = $request->business_details;

    	// $business->event_description = $request->description;
    	$business->update();
    	
    	Session::flash("success", "Business updated successfuly.");

    	return redirect("admin/businesses");
    	
    }
    public function updateApproveStatus(Request $request){
    	try{
    		$business = Business::find($request->business_id);
	    	$business->business_is_approved = (int) $request->value;
	    	$business->update();
	    	$message = ($request->value === 0) ? "Business disapproved" : "Business approved";

	    	return response()->json(["status" => true, "message" => $message] ,200);
    	}catch(\Exception $e){
			return response()->json(["status" => true, "message" => $e->getMessage()] ,200);    		
    	}
    	
    }
    public function deleteMedia(Request $request){
    	// return "hello";
    	try{
    		BusinessImage::where("_id", $request->media_id)->delete();
	        Session::put('media_tab',1);
	    	return response()->json(["status" => true, "message" => "Media has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 200);
    	}
    	
    }
    public function deleteRecommendation(Request $request){
    	// return "hello";
    	try{
    		BusinessRecommendations::where("_id", $request->id)->delete();

    		$business = Business::find($request->business_id);
    		$business->business_total_recommended = --$business->business_total_recommended;
    		$business->save();

	        Session::put('recommendation_tab',1);
	    	return response()->json(["status" => true, "message" => "Recommendation has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 200);
    	}
    	
    }
    public function deleteLike(Request $request){
        // return "hello";
        try{
            BusinessLike::where("_id", $request->id)->delete();

            $business = Business::find($request->business_id);
            $business->business_total_likes = --$business->business_total_likes;
            $business->save();

            Session::put('likes_tab',1);
            return response()->json(["status" => true, "message" => "Like has been deleted successfuly."], 200);
        }catch(\Exception $e){
            return response()->json(["status" => true, "message" => $e->getMessage()], 200);
        }
        
    }
    public function deleteReport(Request $request){
    	try{
    		BusinessReport::where("_id", $request->id)->delete();
	        Session::put('report_tab',1);
	    	return response()->json(["status" => true, "message" => "Business report has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 200);
    	}
    	
    }

    public function destroy($business_id){

        try{
            Business::find(decodeId($business_id))->delete();
            return response()->json(["status" => true, "message" => "Business deleted successfuly."], 200);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()], 400);
        }
    }
}
