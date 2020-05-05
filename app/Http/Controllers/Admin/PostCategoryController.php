<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PostCategoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view permission']);
    }

    public function index()
    {
        return view('admin.post_categories.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxPostCategories(Request $request)
    {
        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('pcat_name_filter');
        }

        $postCategories = PostCategory::where(function($postCategories) use($request){

                if ($request->has('pcat_name')  || $request->session()->has('pcat_name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('pcat_name_filter', $request->pcat_name);

                    //get custom value
                    $customValue = ($request->pcat_name) ? $request->pcat_name : $request->session()->get('pcat_name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $postCategories->orWhere('pcat_name', 'like', "%$customValue%");
                    }
                }
            })
            ->orderBy('created_at','desc')
            // ->toSql();
            ->get();
        // $postCategories = PostCategory::orderBy('created_at', 'desc')->get();
        return DataTables::of($postCategories)
            ->addColumn('pcat_name', function ($category) {
                return $category->pcat_name;
            })
            ->addColumn('action', function ($category) {
                return '<a title="Edit category" href="'.url('admin/post/categories/'.encodeId($category->_id).'/edit') . '" class="btn btn-xs btn-primary"> <i class="fa fa-edit"></i></a>
                        <a title="Delete category" href="'.url('admin/post/categories',$category->_id).'" class="btn-delete btn btn-xs btn-primary"> <i class="fa fa-trash"></i></a>';

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.post_categories.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        PostCategory::create(['pcat_name'=>$request->category_name]);
        return \redirect('admin/post/categories');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = PostCategory::find(decodeId($id));

        return view('admin.post_categories.edit',compact('category'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $postCategory = PostCategory::find($id);
        $postCategory->pcat_name = $request->category_name;
        $postCategory->update();
        return \redirect('admin/post/categories');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $postCategory  = PostCategory::find($id);
        if ($postCategory) {
            $postCategory->delete();
            return response()->json(['status'=>true,'message'=>'Category has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Category is not deleted']);
        }


    }
}


