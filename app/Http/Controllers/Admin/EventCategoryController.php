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

class EventCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view event_categories|edit event_categories']);
    }

    public function index(){
    	return view("admin.event_categories.index");
    }

    public function getAjaxEventCategories(Request $request){


    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('event_category_name_filter');
        }

    	$eventCategory = EventCategory::where(function($eventCategory) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('event_category_name') || $request->session()->has('event_category_name_filter')) {

                //storing data in session
                $request->session()->put('event_category_name_filter', $request->event_category_name);

                //get custom value
                $customValue = ($request->event_category_name) ? $request->event_category_name : $request->session()->get('event_category_name_filter');

                //filtering data
                if (!empty($customValue)) {
                    $eventCategory->where('name', 'like', "%$customValue%");
                }
            }
        })->orderBy("name", "asc")->get();

    	
    	return DataTables::of($eventCategory)
    		->addColumn('name', function($eventCategory){
    			return ucwords($eventCategory->name);
    		})
    		->addColumn('action', function($eventCategory){
    			$action = ''; 
    			if (Auth::user()->can('edit event_category')) {
    				$action .= '<a href="' . url('admin/event_categories/edit/' . encodeId($eventCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('delete event_category')) {
    				$action .= '<a href="' . url('admin/event_categories/' . encodeId($eventCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action'])
    		->make(true);

    }

    public function create(){
        return view("admin.event_categories.add");
    }
    public function store(Request $request){
        $request->validate([
            'name'              => 'required|max:150'
        ]);

        $eventCategory = new EventCategory();
        $eventCategory->name = $request->name;
        $eventCategory->save();
        
        Session::flash("success", "Event category added successfuly.");
        return redirect()->route('admin.event_category.list');
    }
    public function edit($eventCategoryId){
    	$eventCategoryId = decodeId($eventCategoryId);

    	$eventCategory = EventCategory::where("_id", $eventCategoryId)->first();

    	return view("admin.event_categories.edit", compact("eventCategory"));
    }
    public function update(Request $request, $id){

    	$request->validate([
		    'name' 			    => 'required|max:150'
		]);
    	// return $request->all();
    	$eventCategory = EventCategory::find($id);
    	$eventCategory->name = $request->name;
    	$eventCategory->update();
    	
    	Session::flash("success", "Event category updated successfuly.");
    	return redirect("admin/event_categories");
    }

    public function destroy($eventCategoryId){
    	try{
    		EventCategory::find(decodeId($eventCategoryId))->delete();
    		return response()->json(["status" => true, "message" => "Event category deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => false, "message" => $e->getMessage()], 400);
    	}
    }
}
