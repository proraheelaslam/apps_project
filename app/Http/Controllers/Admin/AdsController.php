<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view ads']);
    }

    public function index()
    {

        return view('admin.ads.index');
    }

    /**
     * Get ads list
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxAds(Request $request)
    {
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('name_filter');
        }
        $ads = Ad::where(function($ads) use($request){
            if ($request->has('name')  || $request->session()->has('name_filter'))
            {
                //storing data in session
                $request->session()->put('name_filter', $request->title);
                //get custom value
                $customValue = ($request->name) ? $request->name : $request->session()->get('name_filter');
                //filtering data
                if (!empty($customValue)) {
                    $ads->where('name', 'like', "%$customValue%");
                }
            }
        })
            ->orderBy('created_at','desc')
            ->get();

        return DataTables::of($ads)
            ->addColumn('name', function ($ad) {
                return "<span style='text-align: left'>$ad->name</span>";
            })
            ->addColumn('unit_id', function ($ad) {
                return "<span style='text-align: left'>$ad->unit_id</span>";
            })
            ->addColumn('app_id', function ($ad) {
                return "<span style='text-align: left'>$ad->app_id</span>";
            })
            ->addColumn('height', function ($ad) {
                return "<span style='text-align: left'>$ad->height</span>";
            })
            ->addColumn('width', function ($ad) {
                return "<span style='text-align: left'>$ad->width</span>";
            })
            ->addColumn('action', function ($ad) {
                $action = '';
                if (Auth::user()->can('edit ads')) {
                    $action .= '<a  href="' . url('admin/ads/' . encodeId($ad->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-edit"></i> Edit</a>';
                }
                if (Auth::user()->can('delete ads')) {
                    $action .= '<a href="' . url('admin/ads/' . encodeId($ad->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
                })
            ->rawColumns(['name','unit_id','app_id','height','width','action', 'title'])
            ->make(true);
    }

    /**
     * Create the new Ads
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Save the ads
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'unit_id' => 'required',
            'app_id' => 'required',
            'height' => 'required',
            'width' => 'required|',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        Ad::create($request->all());
        Session::flash("success", "Ads created successfuly.");
        return \redirect('admin/ads');
    }

    /**
     * Show edit view
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $ad = Ad::find(decodeId($id));
        return view('admin.ads.edit',compact('ad'));
    }

    /**
     * Update ads
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $ads = Ad::find($id);
        $ads->name = $request->name;
        $ads->unit_id = $request->unit_id;
        $ads->app_id = $request->app_id;
        $ads->height = $request->height;
        $ads->width = $request->width;
        $ads->update();
        Session::flash("success", "Ads updated successfuly.");
        return \redirect("admin/ads");
    }

    /**
     * Remove the ads
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ad  = Ad::find(decodeId($id));
        if ($ad) {
            $ad->delete();
            return response()->json(['status'=>true,'message'=>'Ads has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Ads is not deleted']);
        }


    }
}
