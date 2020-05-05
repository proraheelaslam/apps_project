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
use App\Models\BusinessReportReason;
use Session;

class BusinessReportReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view business_report_reason|edit business_report_reason|delete business_report_reason|add business_report_reason']);
    }

    public function index(){
    	return view("admin.business_report_reasons.index");
    }

    public function getAjaxBusinessReportCategories(Request $request){


    	//reset filters
        if ((int)$request->set_session === 0) {
            $request->session()->forget('business_report_reason_name_filter');
        }

    	$businessReportReason = BusinessReportReason::where(function($businessReportReason) use($request){
            /****************************** filter field 1 *************************************/
            if ($request->has('business_report_reason_name') || $request->session()->has('business_report_reason_name_filter')) {

                //storing data in session
                $request->session()->put('business_report_reason_name_filter', $request->business_report_reason_name);

                //get custom value
                $customValue = ($request->business_report_reason_name) ? $request->business_report_reason_name : $request->session()->get('business_report_reason_name_filter');

                //filtering data
                if (!empty($customValue)) {
                    $businessReportReason->where('brreason_name', 'like', "%$customValue%");
                }
            }
        })->orderBy("brreason_name", "asc")->get();

    	// return $businessReportReason;
    	return DataTables::of($businessReportReason)
    		->addColumn('brreason_name', function($businessReportReason){
    			return ucwords($businessReportReason->brreason_name);
    		})
    		->addColumn('action', function($businessReportReason){
    			$action = ''; 
    			if (Auth::user()->can('edit business_report_reason')) {
    				$action .= '<a href="' . url('admin/business_report_reason/edit/' . encodeId($businessReportReason->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
    			}	
    			if (Auth::user()->can('delete business_report_reason')) {
    				$action .= '<a href="' . url('admin/business_report_reason/' . encodeId($businessReportReason->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
    			}

    			return $action;
    		})
    		->rawColumns(['action'])
    		->make(true);

    }

    public function create(){
        return view("admin.business_report_reasons.add");
    }
    public function store(Request $request){
        $request->validate([
            'name'              => 'required|max:150'
        ]);

        $businessReportReason = new BusinessReportReason();
        $businessReportReason->brreason_name = $request->name;
        $businessReportReason->save();
        
        Session::flash("success", "Business report reason added successfuly.");
        return redirect('admin/business_report_reason');
    }
    public function edit($businessReportReasonId){
    	$businessReportReasonId = decodeId($businessReportReasonId);

    	$businessReportReason = BusinessReportReason::where("_id", $businessReportReasonId)->first();

    	return view("admin.business_report_reasons.edit", compact("businessReportReason"));
    }
    public function update(Request $request, $id){

    	$request->validate([
		    'name' 			    => 'required|max:150'
		]);
    	// return $request->all();
    	$businessReportReason = BusinessReportReason::find($id);
    	$businessReportReason->brreason_name = $request->name;
    	$businessReportReason->update();
    	
    	Session::flash("success", "Business report reason updated successfuly.");
    	return redirect("admin/business_report_reason");
    }

    public function destroy($businessReportReasonId){
    	try{
    		BusinessReportReason::find(decodeId($businessReportReasonId))->delete();
    		return response()->json(["status" => true, "message" => "Business report reason deleted successfuly."], 200);
    	}catch(\Exception $e){
    		return response()->json(["status" => false, "message" => $e->getMessage()], 400);
    	}
    }
}
