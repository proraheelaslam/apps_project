<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view pages']);
    }

    public function index()
    {
        return view('admin.pages.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxPostContentPages(Request $request)
    {
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('title_filter');
        }

        $pages = Page::where(function($pages) use($request){

                if ($request->has('title')  || $request->session()->has('title_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('title_filter', $request->title);

                    //get custom value
                    $customValue = ($request->title) ? $request->title : $request->session()->get('title_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $pages->where('title', 'like', "%$customValue%");
                    }
                }
            })
            ->orderBy('created_at','desc')
            ->get();

        // $pages = Page::orderBy('created_at', 'desc')->get();
        return DataTables::of($pages)
            ->addColumn('title', function ($page) {
                return "<span style='text-alighn: left'>$page->title</span>";
            })
            ->addColumn('action', function ($page) {
                $action = '';
                if (Auth::user()->can('edit pages')) {
                    $action .= '<a title="Edit Page" href="' . url('admin/pages/' . encodeId($page->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-edit"></i> Edit</a>';
                }
                return $action;
            })
            ->rawColumns(['action', 'title'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|',
            'description' => 'required',
            'sef_url' => 'required|',
            'page_key' => 'required|',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        Page::create($request->all());
        return \redirect('admin/pages');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $page = Page::find(decodeId($id));
        return view('admin.pages.edit',compact('page'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $page = Page::find($id);
        $page->title = $request->title;
        $page->description = $request->description;
        $page->update();
        Session::flash("success", "Page settings updated successfuly.");
        return \redirect("admin/pages");
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $page  = Page::find($id);
        if ($page) {
            $page->delete();
            return response()->json(['status'=>true,'message'=>'Page has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Page is not deleted']);
        }


    }
}
