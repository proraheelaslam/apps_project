<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\Business;
use App\Models\Neighborhood;
use App\Models\BusinessImage;
use App\Models\BusinessLike;
use App\Models\BusinessRecommendations;
use App\Models\BusinessCategory;
use Session;

class BusinessCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view business_categories|edit business_category|delete business_category|add business_category']);
    }

    public function index(){
    	return view("admin.business_categories.index");
    }

    public function getAjaxBusinessCategories(Request $request){


    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('business_category_name_filter');
        }

    	$businessCategory = BusinessCategory::where(function($businessCategory) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('business_category_name') || $request->session()->has('business_category_name_filter')) {

                //storing data in session
                $request->session()->put('business_category_name_filter', $request->business_category_name);

                //get custom value
                $customValue = ($request->business_category_name) ? $request->business_category_name : $request->session()->get('event_category_name_filter');

                //filtering data
                if (!empty($customValue)) {
                    $businessCategory->where('name', 'like', "%$customValue%");
                }
            }
        })->orderBy("name", "asc")->get();

    	
    	return DataTables::of($businessCategory)
    		->addColumn('name', function($businessCategory){
    			return ucwords($businessCategory->name);
    		})
    		->addColumn('action', function($businessCategory){
    			$action = ''; 
    			if (Auth::user()->can('edit business_category')) {
    				$action .= '<a href="' . url('admin/business_categories/edit/' . encodeId($businessCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('delete business_category')) {
    				$action .= '<a href="' . url('admin/business_categories/' . encodeId($businessCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action'])
    		->make(true);

    }

    public function create(){
        return view("admin.business_categories.add");
    }
    public function store(Request $request){
        $request->validate([
            'name'              => 'required|max:150'
        ]);

        $businessCategory = new BusinessCategory();
        $businessCategory->name = $request->name;
        $businessCategory->save();
        
        Session::flash("success", "Business category added successfuly.");
        return redirect()->route('admin.business_category.list');
    }
    public function edit($businessCategoryId){
    	$businessCategoryId = decodeId($businessCategoryId);

    	$businessCategory = BusinessCategory::where("_id", $businessCategoryId)->first();

    	return view("admin.business_categories.edit", compact("businessCategory"));
    }
    public function update(Request $request, $id){

    	$request->validate([
		    'name' 			    => 'required|max:150'
		]);
    	// return $request->all();
    	$businessCategory = BusinessCategory::find($id);
    	$businessCategory->name = $request->name;
    	$businessCategory->update();
    	
    	Session::flash("success", "Business category updated successfuly.");
    	return redirect("admin/business_categories");
    }

    public function destroy($businessCategoryId){
    	try{
    		BusinessCategory::find(decodeId($businessCategoryId))->delete();
    		return response()->json(["status" => true, "message" => "Business category deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => false, "message" => $e->getMessage()], 400);
    	}
    }
}
