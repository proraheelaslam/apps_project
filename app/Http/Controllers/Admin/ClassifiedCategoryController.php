<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use App\Models\ClassifiedCategory;
use Session;

class ClassifiedCategoryController extends Controller
{
	public function __construct()
    {
        $this->middleware(['role:Super Admin|Neighborhood Manager|Area Manager','permission:view classified categories|edit classified category|delete classified category|view classified category']);
    }
    public function index(){
    	//load index html only

    	return view('admin.classified_categories.index');
    }
    public function getAjaxCategories(Request $request){

    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('classified_category_category_name_filter');
        }

    	$classifiedCategories = ClassifiedCategory::where(function($classifiedCategory) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('classified_category_category_name') || $request->session()->has('classified_category_category_name_filter')) {

                //storing data in session
                $request->session()->put('classified_category_category_name_filter', $request->classified_category_category_name);

                //get custom value
                $customValue = ($request->classified_category_category_name) ? $request->classified_category_category_name : $request->session()->get('classified_category_category_name_filter');

                //filtering data
                if (!empty($customValue)) {
                    $classifiedCategory->where('classicat_name', 'like', "%$customValue%");
                }
            }
        })->orderBy("created_at", "desc")->get();

    	// return $classifiedCategories;
    	return DataTables::of($classifiedCategories)
    		->addColumn('classicat_name', function($classifiedCategory){
    			return ucwords($classifiedCategory->classicat_name);
    		})
    		->addColumn('action', function($classifiedCategory){
    			$action = '';
    			if (Auth::user()->can('edit classified category')) {
    				$action .= '<a href="' . url('admin/classified_category/edit/' . encodeId($classifiedCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			/*if (Auth::user()->can('view classified category')) {
    				$action .= '<a href="' . url('admin/classified_category/' . encodeId($classifiedCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-search"></i> View</a>';
    			}*/	
    			if (Auth::user()->can('delete classified category')) {
    				$action .= '<a href="' . url('admin/classified_category_delete/' . encodeId($classifiedCategory->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action'])
    		->make(true);
    }

    public function show($categoryId){
    	
    }

    public function edit($categoryId){
    	// return $categoryId;
    	$category = ClassifiedCategory::where("_id", decodeId($categoryId))->first();
    	// dd($category);
    	return view("admin.classified_categories.edit", compact("category"));
    }

    public function update(Request $request, $id){

    	$request->validate([
            'name'             => 'required|max:150'
        ]);

    	$category = ClassifiedCategory::where("_id", $id)->update(["classicat_name" => $request->name]);
    	Session::flash("success", "Classified category updated successfully.");

    	return redirect("admin/classified_category");
    }

    public function destroy($categoryId){
    	// return decodeId($categoryId);

    	try{
    		ClassifiedCategory::where("_id", decodeId($categoryId))->delete();

    		return response()->json(["status" => false, "message" => "Category deleted successfully."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => false, "message" => $e->getMessage()], 400);
    	}
    }
}
