<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\Classified;
use App\Models\ClassifiedCategory;
use App\Models\ClassifiedImage;
use App\Models\ClassifiedOffer;
use App\Models\ClassifiedStatus;
use App\Models\Neighborhood;
use App\Models\User;
use Session;

class ClassifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Neighborhood Manager|Area Manager','permission:view classifieds|edit classifieds']);
    }

    public function index(){
    	$neighborhoods = Neighborhood::where(function($query){
            if(!empty(Auth::user()->neighborhoods)){
                $query->whereIn("_id", Auth::user()->neighborhoods);
            }
        })->orderBy("neighborhood_name", "asc")->get();
    	return view("admin.classifieds.index", compact("neighborhoods"));
    }

    public function getAjaxClassifieds(Request $request){


    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('classified_title_filter');
            $request->session()->forget('classified_price_filter');
            $request->session()->forget('classified_neighborhood_filter');
            $request->session()->forget('classified_created_by_filter');
        }

        // return Classified::all();

    	$classifieds = Classified::whereHas("neighborhood", function($query){
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
                ->with(["users", "offers", "categories"])
    			->where(function($classifieds) use($request){
    				/****************************** filter field 1 *************************************/
    				if ($request->has('classified_title') || $request->session()->has('classified_title_filter')) {

	                    //storing data in session
	                    $request->session()->put('classified_title_filter', $request->classified_title);

	                    //get custom value
	                    $customValue = ($request->classified_title) ? $request->classified_title : $request->session()->get('classified_title_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
	                        $classifieds->where('classified_title', 'like', "%$customValue%");
	                    }
	                }
	                /****************************** filter field 2 *************************************/
	                if ($request->has('classified_price') || $request->session()->has('classified_price_filter')) {

	                    //storing data in session
	                    $request->session()->put('classified_price_filter', $request->classified_price);

	                    //get custom value
	                    $customValue = ($request->classified_price) ? $request->classified_price : $request->session()->get('classified_price_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
	                        $classifieds->where('classified_price', '>=', (double) $customValue);
	                    }
	                }
	                /****************************** filter field 3 *************************************/
	                if ($request->has('classified_neighborhood') || $request->session()->has('classified_neighborhood_filter')) {

	                    //storing data in session
	                    $request->session()->put('classified_neighborhood_filter', $request->classified_neighborhood);

	                    //get custom value
	                    $customValue = ($request->classified_neighborhood) ? $request->classified_neighborhood : $request->session()->get('classified_neighborhood_filter');

	                    //filtering data
	                    if (!empty($customValue)) {

	                    	$classifieds->where(function($query) use($customValue){
	                    		$query->whereHas("neighborhood", function($q) use($customValue){
	                    			$q->where(function($q1) use ($customValue) {
	                    				// dd($customValue);
	                                    $q1->where('_id', $customValue);

	                                });
	                    		});
	                    	});
	                    }
	                }
	                /****************************** filter field 4 *************************************/
	                if ($request->has('classified_created_by') || $request->session()->has('classified_created_by_filter')) {

	                    //storing data in session
	                    $request->session()->put('classified_created_by_filter', $request->classified_created_by);

	                    //get custom value
	                    $customValue = ($request->classified_created_by) ? $request->classified_created_by : $request->session()->get('classified_created_by_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
                            $names = explode(" ", $customValue);
                            $names = array_map('strtolower', $names);

                            $classifieds->where(function ($query) use ($names, $customValue) {
                                $query->whereHas('users', function ($q) use ($names, $customValue) {
                                    $q->where(function($q1) use ($names, $customValue) {
                                        // dd($names);
                                        $q1->where('user_fname', 'like', "%$names[0]%");
                                        $q1->when(array_key_exists(1, $names), function($q2) use($names){
                                            $q2->orWhere('user_lname', 'like', "%$names[1]%");    
                                        });
                                    });
                                });
                            });
	                        /*$classifieds->orWhere(function ($query) use ($customValue) {
	                            $query->whereHas('users', function ($q) use ($customValue) {
	                                $q->where(function($q1) use ($customValue) {
	                                    $q1->where('user_fname', 'like', "%$customValue%")
	                                    ->orWhere('user_lname', 'like', "%$customValue%");

	                                });
	                            });
	                        });*/
	                    }
	                }
    			})
			// ->toSql();
			->orderBy("created_at", "desc")->get();

    	// return $classifieds;
    	return DataTables::of($classifieds)
    		->addColumn('title', function($classified){
    			return ucwords($classified->classified_title);
    		})
    		->addColumn('classified_price', function($classified){
    			return $classified->classified_price;
    		})
            ->addColumn('neighborhood_name', function($classified){
                return $classified->neighborhood->neighborhood_name;
            })
    		->addColumn('created_by', function($classified){
    			return $classified->users->full_name;
    		})
    		->addColumn('action', function($classified){
    			$action = '';
    			if (Auth::user()->can('edit classifieds')) {
    				$action .= '<a href="' . url('admin/classifieds/edit/' . encodeId($classified->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('view classifieds')) {
    				$action .= '<a href="' . url('admin/classifieds/' . encodeId($classified->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-search"></i> View</a>';
    			}	
    			if (Auth::user()->can('delete classifieds')) {
    				$action .= '<a href="' . url('admin/classifieds/' . encodeId($classified->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action'])
    		->make(true);

    }

    public function show($classifiedId){
    	$classifiedId = decodeId($classifiedId);
    	$classifiedDetail = Classified::with(["classified_images", "users", "offers", "offers.users", "categories", "neighborhood" => function($query){
                    $query->select('neighborhood_name');
                }])->where("_id", $classifiedId)->first();


    	// $classifiedDetail["classified_images"] = array();
    	return view("admin.classifieds.classified_detail", compact("classifiedDetail"));
    }
    public function edit($classifiedId){
    	$classifiedId = decodeId($classifiedId);

    	$classified = Classified::with(["classified_images", "users", "offers", "categories", "neighborhood" => function($query){
                    $query->select('neighborhood_name');
                }])->where("_id", $classifiedId)->first();
        $categories = ClassifiedCategory::all();
    	$neighborhoods = Neighborhood::all();

    	return view("admin.classifieds.edit", compact("classified", "categories", "neighborhoods"));
    }
    public function update(Request $request, $id){

    	$request->validate([
            'title'             => 'required|max:150',
		    'price' 			=> 'required',
		    'category' 			=> 'required',
		    'description' 		=> 'required|max:600',
		]);
    	// return $request->all();
    	$classified = Classified::find($id);
        $classified->classified_title = $request->title;
    	$classified->classified_price = $request->price;
    	$classified->classicat_id = $request->category;
    	$classified->classified_description = $request->description;
    	$classified->update();
    	
    	Session::flash("success", "Classified updated successfuly.");

    	return redirect("admin/classifieds");
    }

    public function deleteParticipant(Request $request){
    	try{
    		EventParticipant::where("event_id", $request->event_id)->where("user_id", $request->user_id)->delete();
	        Session::put('participant_tab',1);
	    	return response()->json(["status" => true, "message" => "Participant has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 200);
    	}
    	
    }

    public function deleteMedia(Request $request){
        // return "hello";
        try{
            ClassifiedImage::where("_id", $request->media_id)->delete();
            Session::put('media_tab',1);
            return response()->json(["status" => true, "message" => "Media has been deleted successfuly."], 200);
        }catch(\Exception $e){
            return response()->json(["status" => true, "message" => $e->getMessage()], 200);
        }
        
    }

    public function deleteOffer(Request $request){
    	// return $request->all();
    	try{
    		ClassifiedOffer::where("_id", $request->offer_id)->delete();
	        Session::put('offer_tab',1);
	    	return response()->json(["status" => true, "message" => "Offer has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 400);
    	}
    	
    }

    public function destroy($classifiedId){
    	try{
    		Classified::find(decodeId($classifiedId))->delete();
    		return response()->json(["status" => true, "message" => "Classified deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => false, "message" => $e->getMessage()], 400);
    	}
    }




    //
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function invite(Request $request)
    {
        try {
            if (isset($request->event)) {
                $query_string = 'event';
                $id = $request->event;
            }
            $data['device'] = $this->userAgent();

            if ($this->userAgent() == 'android') {
                return redirect('nextneighbourApp://nextneighbourApp.com/?' . $query_string . '=' . $id);
            } else if ($this->userAgent() == 'ios') {
                return redirect('nextneighbourApp://www.nextneighbourApp.com/' . $query_string . '=' . $id);
            } else {
                return redirect('/');
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @return string
     */
    private function userAgent()
    {
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        if ($iPad || $iPhone || $iPod) {
            return 'ios';
        } else if ($android) {
            return 'android';
        } else {
            return 'pc';
        }
    }


    public function invites()
    {
        $collection = collect([
            [
                'user_id' => '1',
                'title' => 'Helpers in Laravel',
                'content' => 'Create custom helpers in Laravel',
                'category' => 'php'
            ],
            [
                'user_id' => '2',
                'title' => 'Testing in Laravel',
                'content' => 'Testing File Uploads in Laravel',
                'category' => 'php'
            ],
            [
                'user_id' => '3',
                'title' => 'Telegram Bot',
                'content' => 'Crypto Telegram Bot in Laravel',
                'category' => 'php'
            ],
        ]);

        dd($collection);



    }
}
