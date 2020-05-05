<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostImage;
use App\Models\PostReply;
use App\Models\PostThank;
use App\Models\PostQuestion;
use App\Models\PostAnswer;
use App\Models\UserPost;
use App\Models\Neighborhood;
use DB; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Area Manager|Neighborhood Manager','permission:view posts']);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pollPostsIndex(Request $request)
    {
        // dd(Auth::user());
        $neighborhoods = Neighborhood::where(function($query){
            if(!empty(Auth::user()->neighborhoods)){
                $query->whereIn("_id", Auth::user()->neighborhoods);
            }
        })->orderBy("neighborhood_name", "asc")->get();
        return view("admin.posts.poll_index", compact("neighborhoods"));

    }
    public function alertPostsIndex(Request $request)
    {
        $neighborhoods = Neighborhood::orderBy("neighborhood_name", "asc")->get();
        return view("admin.posts.alert_index", compact("neighborhoods"));

    }
    public function messagePostsIndex(Request $request)
    {
        $neighborhoods = Neighborhood::orderBy("neighborhood_name", "asc")->get();
        return view("admin.posts.message_index", compact("neighborhoods"));

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAjaxPosts(Request $request)
    {
        define("POST_TYPE", $request->post_type);
        // return $request->session()->all();
        //reset filters
        if ((int) $request->set_session === 0) {

            $request->session()->forget(POST_TYPE.'_upost_title_filter');
            $request->session()->forget(POST_TYPE.'_user_filter');
            $request->session()->forget(POST_TYPE.'_neighborhood_name_filter');
        }

        $posts = UserPost::whereHas("neighborhood", function($query){
                    //START ADDING FILTER ON THE BASE OF ROLE SCREEN ----------------------------------------

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
            ->with(['post_images', 'users','answers'])
            ->where(function ($posts) use ($request) {

                if ($request->has(POST_TYPE.'_upost_title') || $request->session()->has(POST_TYPE.'_upost_title_filter')) {

                    //storing data in session
                    $request->session()->put(POST_TYPE.'_upost_title_filter', $request->input(POST_TYPE."_upost_title"));

                    //get custom value
                    $customValue = ($request->input(POST_TYPE."_upost_title")) ? $request->input(POST_TYPE."_upost_title") : $request->session()->get(POST_TYPE.'_upost_title_filter');

                    //filtering data
                    if (!empty($customValue)) {
                        $posts->orWhere('upost_title', 'like', "%$customValue%");
                    }
                }
                if ($request->has(POST_TYPE.'_user') || $request->session()->has(POST_TYPE.'_user_filter')) {
                    //storing data in session
                    $request->session()->put(POST_TYPE.'_user_filter', $request->input(POST_TYPE."_user"));

                    //get custom value
                    $customValue = ($request->input(POST_TYPE."_user")) ? $request->input(POST_TYPE."_user") : $request->session()->get(POST_TYPE.'_user_filter');

                    //filtering data
                    if (!empty($customValue)) {

                        $names = explode(" ", trim($customValue));
                        $names = array_map('strtolower', $names);

                        $posts->where(function ($query) use ($names, $customValue) {
                            $query->whereHas('users', function ($q) use ($names, $customValue) {
                                $q->where(function($q1) use ($names, $customValue) {
                                    $q1->where('user_fname', 'like', "%$names[0]%");
                                    $q1->when(array_key_exists(1, $names), function($q2) use($names){
                                        $q2->orWhere('user_lname', 'like', "%$names[1]%");    
                                    });
                                    
                                });
                            });
                        });
                    }
                }
                if ($request->has(POST_TYPE.'_neighborhood_name') || $request->session()->has(POST_TYPE.'_neighborhood_name_filter')) {
                    //storing data in session
                    $request->session()->put(POST_TYPE.'_neighborhood_name_filter', $request->input(POST_TYPE."_neighborhood_name"));
                    //get custom value
                    $customValue = ($request->input(POST_TYPE."_neighborhood_name")) ? $request->input(POST_TYPE."_neighborhood_name") : $request->session()->get(POST_TYPE.'_neighborhood_name_filter');

                    //filtering data
                    if (!empty($customValue)) {

                        $posts->where(function ($query) use ($customValue) {
                            $query->whereHas('neighborhood', function ($q) use ($customValue) {
                                $q->where("_id", $customValue);
                            });
                        });
                    }
                }
            })
            ->where("post_type", $request->post_type)
            // ->toSql();
            ->orderBy('created_at', 'desc')
            ->get();

            //dd($posts->toArray());
        return DataTables::of($posts)
            ->addColumn('upost_title', function ($post) {
                return "<p class='break-words'>$post->upost_title</p>";
            })
            ->addColumn('post_type', function ($post) {
                return ucfirst($post->post_type);
            })
            ->addColumn('post_user', function ($post) {
               return "<p class='break-words'>".$post->users->full_name."</p>";

            })
            ->addColumn('votes', function ($post) {
                return count($post->answers);
            })
            ->addColumn('neighborhood_name', function ($post) {
                return "<p class='break-words'>".$post->neighborhood->neighborhood_name."</p>";
            })
            ->addColumn('action', function ($post) use($request){

                $action = '';
                $sub_type = $request->post_type; 
                if (Auth::user()->can('edit '.$sub_type.' posts')) {
                    $action .= '<a href="' . url('admin/posts/edit/' . encodeId($post->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-pencil"></i> Edit</a>';
                }
                if (Auth::user()->can('view '.$sub_type.' posts')) {
                    $action .= '<a href="' . url('admin/posts/' . encodeId($post->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable margin-top-5px"><i class="fa fa-search"></i> View</a>';
                }
                if (Auth::user()->can('delete '.$sub_type.' posts')) {
                    $action .= '<a href="' . url('admin/posts/' . encodeId($post->_id)) . '" class="btn btn-sm btn-circle btn-default btn-editable btn-delete margin-top-5px"> <i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action', 'upost_title', 'post_user', 'neighborhood_name'])
            ->make(true);
    }

    private function getPostListing($request){

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($postId)
    {

        $postId = decodeId($postId);
        if (!$userPos = UserPost::find($postId)) {
            Session::flash('error', 'post id is invalid.');
            return redirect()->back();
        }

        
        $postThank  = PostThank::where('user_id', Auth::id())->get();
        $postAnswer = PostAnswer::where('user_id', Auth::id())->get();
        // dd($postAnswer);
        $posts      = UserPost::with('users', 'post_images', 'post_questions')->where('_id', $postId)->get();

        // mapping data
        $posts->map(function ($post) use ($postThank, $postAnswer) {

            $post['is_liked']   = $postThank->contains('upost_id', $post->_id);
            $totalQuestion      = PostQuestion::where('upost_id', $post->_id)->get();

            $post->post_questions->map(function ($question) use ($postAnswer, $totalQuestion, $post) {
                $qAnswers           = PostAnswer::where('pquestion_id', $question->_id)->get();
                $totalAns           = PostAnswer::where('upost_id', $post->_id)->get();
                $singleTotalAnswer  = count($qAnswers);
                $percentage = 0;

                if ($singleTotalAnswer != 0 || count($totalAns) != 0) {
                    $percentage = ($singleTotalAnswer / count($totalAns)) * 100;
                }
                $question['is_answer'] = $postAnswer->contains('pquestion_id', $question->_id);
                $question['percentage'] = round($percentage);

                return $question;
            });
            return $post;
        });
        $userAnswers = PostAnswer::with(["users", "questions"])->where("upost_id", $postId)->get();
        // dd($posts[0]);
        
        return view('admin.posts.post_detail')->with(["post" => $posts[0], "userAnswers" => $userAnswers]);
    }

    /**
     * @param $postId
     * @param bool $getArrayData
     * @return UserPost|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function edit($postId, $getArrayData = false)
    {

        $postId = decodeId($postId);
        if (!$userPos = UserPost::find($postId)) {
            Session::flash('error', 'post id is invalid.');
            return redirect()->back();
        }

        
        $postThank  = PostThank::where('user_id', Auth::id())->get();
        $postAnswer = PostAnswer::where('user_id', Auth::id())->get();
        // dd($postAnswer);
        $posts      = UserPost::with('users', 'post_images', 'post_questions')->where('_id', $postId)->get();

        // return $posts;
        $post_type = strtolower($posts[0]->post_type);
        // return $post_type;
        // mapping data
        $posts->map(function ($post) use ($postThank, $postAnswer) {

            $post['is_liked']   = $postThank->contains('upost_id', $post->_id);
            $totalQuestion      = PostQuestion::where('upost_id', $post->_id)->get();

            $post->post_questions->map(function ($question) use ($postAnswer, $totalQuestion, $post) {
                $qAnswers           = PostAnswer::where('pquestion_id', $question->_id)->get();
                $totalAns           = PostAnswer::where('upost_id', $post->_id)->get();
                $singleTotalAnswer  = count($qAnswers);
                $percentage = 0;

                if ($singleTotalAnswer != 0 || count($totalAns) != 0) {
                    $percentage = ($singleTotalAnswer / count($totalAns)) * 100;
                }
                $question['is_answer'] = $postAnswer->contains('pquestion_id', $question->_id);
                $question['percentage'] = round($percentage);

                return $question;
            });
            return $post;
        });
        $userAnswers = PostAnswer::with(["users", "questions"])->where("upost_id", $postId)->get();
        // return $posts[0];

        if ($getArrayData === true) {
            return $posts[0];
        }
        switch ($post_type) {
            case 'message':
                $view_name = "edit_message_posts";
            break;
            case 'alert':
                $view_name = "edit_alert_posts";
            break;
            case 'poll':
                $view_name = "edit_poll_posts";
            break;
        }

        return view('admin.posts.'.$view_name)->with(["post" => $posts[0], "userAnswers" => $userAnswers]);
    }

    public function updatePollPosts(Request $request, $postId){


        $request->validate([
            'title'             => 'required|max:500',
            'options.*.*'           => 'required|min:5|max:50',
        ], ["options.*.*" => "Option field/s should have 50 characters maximum and not to be empty."]);


        $postId = decodeId($postId);
        $post = UserPost::find($postId);
        $post->upost_description = $request->title;
        $post->update();


        //get data for delete table ids
        $postData = $this->edit(encodeId($postId), true);
        $postQuestions = $postData["post_questions"]->toArray();
        $allQuestionIds  = array_column($postQuestions, "_id");

        // dd($postData);

        $options = $request->options;
        $new = 0;
        $updateId = [];


        try{
            foreach ($options as $key => $value) {
                if (is_array($value)) {
                    if (strpos($key, "existing-") !== false) {
                        $value = (is_array($value)) ? $value[0] : $value;
                        $idArray = explode("existing-", $key);
                        $tableId = $idArray[1];

                        $postQuestion = PostQuestion::find($tableId);
                        $postQuestion->upost_id = $postId;
                        $postQuestion->pquestion_question = $value;
                        $postQuestion->update();

                        //update this record in table
                        array_push($updateId, $tableId);
                    }
                }else{
                    //add new option in database
                    // $new++;

                    $postQuestion = new PostQuestion;
                    $postQuestion->upost_id = $postId;
                    $postQuestion->pquestion_question = $value;
                    $postQuestion->save();
                }
            }
            //now delete the table id
            $deleteIds = array_diff($allQuestionIds, $updateId);
            if (!empty($deleteIds)) {
                foreach ($deleteIds as $key => $tableId) {
                    $postQuestion = PostQuestion::find($tableId)->delete();
                }
            }

            Session::flash("success", "Poll post updated successfuly.");
            return redirect("admin/poll_posts");
        }catch(\Exception $e){

            Session::flash("error", $e->getMessage());
            return redirect()->back();
        }


        
        // dd($deleteIds);

        return "new record is ". $new." and updating records are ". $update;
    }

    public function updateAlertPosts(Request $request, $postId){

        $request->validate([
                'description'       => 'required|max:500',
            ]);
        // return $postId;
        $post = UserPost::findOrFail($postId);
        

        $post->upost_description = $request->description;
        $post->update();

        // $view_name = "admin.alert_posts.list";

        Session::flash("success", "Alert post has been updated successfully.");
        return redirect("admin/alert_posts");
        
    }

    public function updateMessagePosts(Request $request, $postId){
        // return $request->all();
        $post = UserPost::find($postId);
        


        $request->validate([
            'title'             => 'required|max:100',
            'description'       => 'required|min:5|max:500',
        ]);
        $post->upost_title = $request->title;
        $post->upost_description = $request->description;

        $post->update();
        $view_name = "admin.message_posts.list";

        Session::flash("success", "Message post has been updated successfully.");
        return redirect("admin/message_posts");
        
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        PostReply::find($request->comment_id)->delete();
        Session::flash('success', 'Comment has been deleted successfuly');
        Session::put('comment_tab',1);
        return response()->json(['status' => true, 'message' => 'Comment has been deleted successfuly']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteThankUser(Request $request)
    {
        PostThank::where(['upost_id' => $request->post_id, 'user_id' => $request->user_id])->delete();
        Session::flash('success', 'Thank User has been deleted successfuly');
        Session::put('thanks_tab',1);
        return response()->json(['status' => true, 'message' => 'Thank User has been deleted successfuly']);
    }

    public function deletePostImage(Request $request)
    {

        $postImage = PostImage::find($request->post_image_id);
        if (File::exists(public_path('upload/posts/' . $postImage->pimg_image_file))) {
            File::delete($postImage->pimg_image_file);
            $postImage->delete();
            return response()->json(['status' => true, 'message' => 'Post image has been deleted']);
        } else {
            return response()->json(['status' => false, 'message' => 'Post image does not exists']);
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserAnswer(Request $request)
    {
        PostAnswer::find($request->answer_id)->delete();
        Session::flash('success', 'Answer has been deleted successfuly.');
        Session::put('userAnswer_tab',1);
        return response()->json(['status' => true, 'message' => 'Answer has been deleted successfuly.']);
    }

    public function getComment(Request $request)
    {
        $comment = PostReply::find($request->comment_id);
        return response()->json(['status'=>true,'message'=>$comment->preply_comment,'id'=>$comment->_id]);
    }

    public function updateComment(Request $request)
    {
        $comment = PostReply::find($request->comment_id);
        if ($comment) {
            $comment->preply_comment = $request->comment;
            $comment->save();
            Session::put("comment_tab", 1);
            Session::flash("success", 'Comment has been updated successfuly');
            return redirect()->back();
        }else {
            Session::put("comment_tab", 1);
            Session::flash("error", 'Comment is not update');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $post = UserPost::find(decodeId($id));
        //dd($post);
        if ($post) {
            $post->delete();
            return response()->json(['status' => true, 'message' => 'Post has been deleted']);
        } else {
            return response()->json(['status' => false, 'message' => 'Post is not deleted']);
        }


    }
}
