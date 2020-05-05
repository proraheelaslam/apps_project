<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventParticipant;
use App\Models\EventImage;
use App\Models\Neighborhood;
use Session;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view events|edit events']);
    }

    public function index(){
    	$neighborhoods = Neighborhood::where(function($query){
            if(!empty(Auth::user()->neighborhoods)){
                $query->whereIn("_id", Auth::user()->neighborhoods);
            }
        })->orderBy("neighborhood_name", "asc")->get();
    	return view("admin.events.index", compact("neighborhoods"));
    }

    public function getAjaxEvents(Request $request){


    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('event_title_filter');
            $request->session()->forget('event_location_filter');
            $request->session()->forget('event_neighborhood_filter');
            $request->session()->forget('event_created_by_filter');
            $request->session()->forget('event_date_time_filter');
        }

    	$events = Event::whereHas("neighborhood", function($query){
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
                ->with(["event_images", "categories", "users"])
    			->where(function($events) use($request){
    				/****************************** filter field 1 *************************************/
    				if ($request->has('event_title') || $request->session()->has('event_title_filter')) {

	                    //storing data in session
	                    $request->session()->put('event_title_filter', $request->event_title);
	                    //get custom value
	                    $customValue = ($request->event_title) ? $request->event_title : $request->session()->get('event_title_filter');
	                    //filtering data
	                    if (!empty($customValue)) {
                            $events->where('title', 'like', "%".$customValue."%");
	                    }
	                }
	                /****************************** filter field 2 *************************************/
	                if ($request->has('event_location') || $request->session()->has('event_location_filter')) {
	                    //storing data in session
	                    $request->session()->put('event_location_filter', $request->event_location);

	                    //get custom value
	                    $customValue = ($request->event_location) ? $request->event_location : $request->session()->get('event_location_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
	                        $events->where('event_locations', 'like', "%$customValue%");
	                    }
	                }
	                /****************************** filter field 3 *************************************/
	                if ($request->has('event_neighborhood') || $request->session()->has('event_neighborhood_filter')) {

	                    //storing data in session
	                    $request->session()->put('event_neighborhood_filter', $request->event_neighborhood);

	                    //get custom value
	                    $customValue = ($request->event_neighborhood) ? $request->event_neighborhood : $request->session()->get('event_neighborhood_filter');

	                    //filtering data
	                    if (!empty($customValue)) {

	                    	$events->where(function($query) use($customValue){
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
	                if ($request->has('event_created_by') || $request->session()->has('event_created_by_filter')) {
	                    //storing data in session
	                    $request->session()->put('event_created_by_filter', $request->event_created_by);
	                    //get custom value
	                    $customValue = ($request->event_created_by) ? $request->event_created_by : $request->session()->get('event_created_by_filter');
	                    //filtering data
	                    if (!empty($customValue)) {

                            $names = explode(" ", $customValue);
                            $names = array_map('strtolower', $names);

                            $events->where(function ($query) use ($names, $customValue) {
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
	                    }
	                }
	                /****************************** filter field 5 *************************************/
	                if ($request->has('event_date_time') || $request->session()->has('event_date_time_filter')) {

	                    //storing data in session
	                    $request->session()->put('event_date_time_filter', $request->event_date_time);

	                    //get custom value
	                    $customValue = ($request->event_date_time) ? $request->event_date_time : $request->session()->get('event_date_time_filter');

	                    //filtering data
	                    if (!empty($customValue)) {
	                    	$customDate = date("Y-m-d", strtotime($customValue));
	                        $events->whereBetween('event_date', [$customDate." 00:00:00", $customDate." 23:59:59"]);
	                    }
	                }
    			})
			// ->toSql();
			->orderBy("event_date", "desc")->get();

    	// return $events;
    	return DataTables::of($events)
    		->addColumn('title', function($event){
                $customTitle = $event->title;
    			return "<p style='word-break:break-all'>$customTitle</p>";
    		})
    		->addColumn('event_locations', function($event){
    			return ucwords($event->event_locations);
    		})
    		->addColumn('neighborhood_name', function($event){
    			return $event->neighborhood->neighborhood_name;
    		})
    		->addColumn('created_by', function($event){
    			return $event->users->full_name;
    		})
    		->addColumn('event_date', function($event){
    			return date('Y M d h:i A', strtotime($event->event_date));
    		})
    		->addColumn('action', function($event){
    			$action = '';
    			if (Auth::user()->can('edit events')) {
    				$action .= '<a href="' . url('admin/events/edit/' . encodeId($event->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('view events')) {
    				$action .= '<a href="' . url('admin/events/' . encodeId($event->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-search"></i> View</a>';
    			}	
    			if (Auth::user()->can('delete events')) {
    				$action .= '<a href="' . url('admin/events/' . encodeId($event->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action', 'title'])
    		->make(true);

    }

    public function show($eventId){
    	$eventId = decodeId($eventId);
    	$eventDetail = Event::with(["event_images", "categories", "categories", "participants.users"])->where("_id", $eventId)->first();
    	// return $eventDetail;
    	return view("admin.events.event_detail", compact("eventDetail"));
    }
    public function edit($eventId){
    	$eventId = decodeId($eventId);

    	$event = Event::with(["event_images", "categories", "categories", "participants.users"])->where("_id", $eventId)->first();
    	$categories = EventCategory::orderBy("name", "asc")->get();
    	$neighborhoods = Neighborhood::all();

    	return view("admin.events.edit", compact("event", "categories", "neighborhoods"));
    }
    public function update(Request $request, $id){

    	$request->validate([
		    'title' 			=> 'required|max:100',
		    'category' 			=> 'required',
		    'event_date_time' 	=> 'required|Date',
		    'location' 			=> 'required|max:250',
		    // 'neighborhoods' 	=> 'required',
		    'description' 		=> 'required|max:500',
		]);
    	// return $request->all();
    	$event = Event::find($id);
    	$event->title = $request->title;
    	$event->ecategory_id = $request->category;
    	$event->event_date = date('Y-m-d H:i:s', strtotime($request->event_date_time));
    	$event->event_locations = $request->location;
    	$event->event_description = $request->description;
    	$event->update();
    	
    	Session::flash("success", "Event updated successfuly.");

    	return redirect("admin/events");
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
    		EventImage::where("_id", $request->media_id)->delete();
	        Session::put('media_tab',1);
	    	return response()->json(["status" => true, "message" => "Media has been deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => true, "message" => $e->getMessage()], 200);
    	}
    	
    }

    public function destroy($eventId){
    	try{
    		Event::find(decodeId($eventId))->delete();
    		return response()->json(["status" => true, "message" => "Event deleted successfuly."], 200);
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
