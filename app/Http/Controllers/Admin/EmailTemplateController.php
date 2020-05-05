<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view emails']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.emails.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxEmails(Request $request)
    {
        // $emails = EmailTemplate::orderBy('created_at', 'desc')->get();

        //reset filters
        if ((int) $request->set_session === 0){
            $request->session()->forget('email_name_filter');
            $request->session()->forget('email_from_filter');
            $request->session()->forget('email_subject_filter');
            $request->session()->forget('email_content_filter');
        }

         $emails = EmailTemplate::where(function($emails) use($request){

                if ($request->has('email_name')  || $request->session()->has('email_name_filter')) 
                {
                    
                    //storing data in session
                    $request->session()->put('email_name_filter', $request->email_name);

                    //get custom value
                    $customValue = ($request->email_name) ? $request->email_name : $request->session()->get('email_name_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $emails->where('name', 'like', "%$customValue%");
                    }
                }


                if ($request->has('email_from')  || $request->session()->has('email_from_filter'))
                {
                    //storing data in session
                    $request->session()->put('email_from_filter', $request->email_from);

                    //get custom value
                    $customValue = ($request->email_from) ? $request->email_from : $request->session()->get('email_from_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $emails->where('from', 'like', "%$customValue%");
                    }
                }
                if ($request->has('email_subject')  || $request->session()->has('email_subject_filter'))
                {
                    //storing data in session
                    $request->session()->put('email_subject_filter', $request->email_subject);

                    //get custom value
                    $customValue = ($request->email_subject) ? $request->email_subject : $request->session()->get('email_subject_filter');

                    //filtering data
                    if (!empty($customValue)) {
                       $emails->where('subject', 'like', "%$customValue%");
                    }
                }
        })
            ->orderBy('created_at','desc')
            // ->toSql();
            ->get();

            // return $emails;

        return DataTables::of($emails)
            ->addColumn('name', function ($email) {
                return $email->name;
            })
            ->addColumn('from', function ($email) {
                return strip_tags($email->from);
            })
            ->addColumn('subject', function ($email) {
                $subject = ucwords($email->subject);
                return "<p class='break-words'>".$subject."</p>";
            })
            ->addColumn('action', function ($email) {
                $action = '';
                if (Auth::user()->can('edit emails')) {
                    $action .= '<a title="Edit Email template" href="' . url('admin/emails/' . encodeId($email->_id) . '/edit') . '" class="btn btn-sm btn-circle btn-default btn-editable"> <i class="fa fa-edit"></i> Edit</a>';
                }
                return $action;
            })
            ->rawColumns(['action', 'subject'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.emails.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'from' => 'required',
            'subject' => 'required',
            'content' => 'required',
            'key' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        EmailTemplate::create($request->all());
        return \redirect('admin/emails');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $email = EmailTemplate::find(decodeId($id));
        return view('admin.emails.edit',compact('email'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $email = EmailTemplate::find($id);
        $email->name = $request->name;
        $email->subject = $request->subject;
        $email->content = $request->email_content;
        $email->update();
        Session::flash("success", "Email settings updated successfuly.");
        return \redirect("admin/emails");
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $page  = EmailTemplate::find($id);
        if ($page) {
            $page->delete();
            return response()->json(['status'=>true,'message'=>'Email template has been deleted']);
        }else{
            return response()->json(['status'=>false,'message'=>'Email template is not deleted']);
        }


    }
}
