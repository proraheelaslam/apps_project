<?php

namespace App\Http\Controllers\Api\V1;


use App\Jobs\PostCommentNotification;
use App\Jobs\PostNotification;
use App\Models\PostAnswer;
use App\Models\PostImage;
use App\Models\PostQuestion;
use App\Models\PostReply;
use App\Models\PostThank;
use App\Models\User;
use App\Models\UserPost;
use App\Models\ViewPost;
use App\Traits\ApiResponse;
use App\Traits\NotificationTrait;
use App\Traits\PostApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class PostApiController extends Controller
{
    use ApiResponse, PostApiTrait, NotificationTrait;

    /**
     * Get all posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = $this->posts();
       // $posts = $this->postsPagination($posts);
        $message = Lang::get('api.neighbood_post_message');
        if (!empty($posts)) {
            return $this->sucessResponse(true, $message, $posts);
        }
        return $this->sucessResponse(true, $message, $posts);
    }

    public function index2()
    {
        $posts = $this->post();
        $message = Lang::get('api.neighbood_post_message');
        if (!empty($posts)) {
            return $this->sucessResponse(true, $message, $posts);
        }
        return $this->sucessResponse(true, $message, $posts);
    }

    public function creates(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_type' => 'in:message,alert,poll',
                'message' => 'required',
                'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $userPost = new UserPost();
            if ($request->post_type == 'message') {
                $validator = Validator::make($request->all(), [
                    'subject' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $userPost = $this->postMessage($request, $userPost);
                // run job

                $message = Lang::get('api.post_saved_message');
            }
            if ($request->post_type == 'alert') {
                $userPost = $this->postAlert($userPost);
                $message = Lang::get('api.alert_post_message');
            }
            $userPost = $this->savePost($request, $userPost, 'create');

            if ($request->post_type == 'poll') {
                $validator = Validator::make($request->all(), [
                    'message' => 'required',
                    'expiry_date' => 'required',
                    'questions' => 'required|json'
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $questions = json_decode($request->questions);
                $question = $this->postPoll($questions, $userPost);
                $question->post_questions;
                $message = Lang::get('api.poll_post_message');
            }
            $posts = $this->posts($userPost->_id);
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Create new Post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_type' => 'in:message,alert,poll',
                'message' => 'required',
                'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            if (!$this->checkUserStatus(Auth::id())) {
                $message = Lang::get('api.status_not_approved_message');
                return $this->errorResponse(false, $message, []);
            }
            $userPost = new UserPost();
            if ($request->post_type == 'message') {
                $validator = Validator::make($request->all(), [
                    'subject' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $userPost = $this->postMessage($request, $userPost);
                $message = Lang::get('api.post_saved_message');
            }
            if ($request->post_type == 'alert') {
                $userPost = $this->postAlert($userPost);
                $message = Lang::get('api.alert_post_message');
            }
            $userPost = $this->savePost($request, $userPost, 'create');

            if ($request->post_type == 'poll') {
                $validator = Validator::make($request->all(), [
                    'message' => 'required',
                    'expiry_date' => 'required',
                    'questions' => 'required|json'
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $questions = json_decode($request->questions);
                $question = $this->postPoll($questions, $userPost);
                $question->post_questions;
                $message = Lang::get('api.poll_post_message');
                dispatch(new PostNotification($userPost, 'poll', 'new_poll_created'))->delay(now()->addSecond(1));
            }
            $posts = $this->posts($userPost->_id);
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Get Post Detail by post_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDetail($postId)
    {
        try {
            $posts = $this->posts($postId);
            if (!empty($posts)) {
                $message = Lang::get('api.post_detail_message');
                return $this->sucessResponse(true, $message, $posts);
            } else {

                $message = Lang::get('api.post_detail_message');
                return $this->sucessResponse(true, $message, $posts);
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Update the Post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_type' => 'in:message,alert,poll',
                'post_id' => 'required|exists:user_posts,_id',
                'message' => 'required',
                'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',

            ]);
            if ($validator->fails()) {
                return $this->validationResponse(false, $validator, $data = []);
            }

            if (isset($request->images)) {
                $validator = Validator::make($request->all(), [
                    'images' => 'required|json'
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $images = json_decode($request->images);
                foreach ($images as $img) {
                    if (!empty($img->image_id)) {
                        $post_image = PostImage::find($img->image_id);
                        if($post_image){
                            $post_image->order_id = $img->order_id;
                            $post_image->save();
                        }                     
                    } 
                }
            }
            $userPost = UserPost::find($request->post_id);
            if ($request->post_type == 'message') {
                $validator = Validator::make($request->all(), [
                    'subject' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                if ($userPost->upost_title != $request->subject) {
                    $userPost->is_edited = 1;
                }
                $userPost = $this->postMessage($request, $userPost);

                $message = Lang::get('api.post_update_message');

            }
            if ($request->post_type == 'alert') {
                $userPost = $this->postAlert($userPost);
                $message = Lang::get('api.alert_post_update_message');
            }
            $userPost = $this->savePost($request, $userPost, 'update');
            if ($request->post_type == 'poll') {
                $validator = Validator::make($request->all(), [
                    'message' => 'required',
                    'expiry_date' => 'required',
                    'questions' => 'required|json'
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

                $questions = json_decode($request->questions);
                foreach ($questions as $q) {
                    if ($q->question_id != 0 && !empty($q->question_id)) {
                        $postQuestion = PostQuestion::find($q->question_id);
                        if ($postQuestion) {
                            $postQuestion->pquestion_question = $q->question;
                            $postQuestion->save();
                        }
                    } else {
                        $newQuestion = new PostQuestion;
                        $newQuestion->upost_id = $userPost->_id;
                        $newQuestion->pquestion_question = $q->question;
                        $newQuestion->save();
                    }
                }
                $message = Lang::get('api.poll_post_update_message');
            }
            $userPost->post_questions;
            $posts = $this->posts($userPost->_id);

            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Save or update the post by $request and $userPost object
     * @param $request
     * @param $userPost
     * @return mixed
     */
    private function savePost($request, $userPost, $type)
    {
        $userPost->user_id = Auth::id();
        $userPost->post_type = $request->post_type;
        $userPost->neighborhood_id = $request->neighborhood;
        $userPost->upost_total_thanks = ($userPost->upost_total_thanks == null ? 0 : $userPost->upost_total_thanks);
        $userPost->upost_total_replies = ($userPost->upost_total_replies == null ? 0 : $userPost->upost_total_replies);
        $userPost->upost_poll_end_date = date('Y-m-d', strtotime($request->expiry_date));
        $userPost->upost_sef_url = str_slug($request->subject);
        $userPost->pstatus_id = $this->postStatus('Pending');
        if ($request->post_type != 'alert') {
            if ($userPost->upost_description != $request->message) {
                $userPost->is_edited = 1;
            }
        }
        $userPost->upost_description = $request->message;
        $userPost->save();

        //
        if ($type == 'create') {
            if ($request->post_type == 'message') {
                dispatch(new PostNotification($userPost, 'message', Config::get('constant.new_post')))->delay(now()->addSecond(1));
            } elseif ($request->post_type == 'alert') {
                dispatch(new PostNotification($userPost, 'alert', Config::get('constant.new_alert')))->delay(now()->addSecond(1));
            } elseif ($request->post_type == 'poll') {
                dispatch(new PostNotification($userPost, 'poll', Config::get('constant.new_poll')))->delay(now()->addSecond(1));
            }
        } else if ($type == 'update') {
            if ($request->post_type == 'message') {
                dispatch(new PostNotification($userPost, 'message', Config::get('constant.post_updated')))->delay(now()->addSecond(1));
            } elseif ($request->post_type == 'alert') {
                dispatch(new PostNotification($userPost, 'alert', Config::get('constant.alert_updated')))->delay(now()->addSecond(1));
            } elseif ($request->post_type == 'poll') {
                dispatch(new PostNotification($userPost, 'poll', Config::get('constant.poll_updated')))->delay(now()->addSecond(1));
            }
        }

        return $userPost;
    }

    /**
     * Update the post Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:post_replies,_id',
                'comment' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = PostReply::find($request->comment_id);
            $reply->preply_comment = $request->comment;
            $reply->is_edited = 1;
            $reply->save();
            $message = Lang::get('api.comment_update_message');
            return $this->sucessResponse(true, $message, []);

        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Delete the Post Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:post_replies,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = PostReply::find($request->comment_id);
            $reply->delete();
            if(isset($reply->is_image_comment) && $reply->is_image_comment ==1 && isset($reply->media_id)){

                $userPost = PostImage::find($reply->media_id);
                if ($userPost->upost_media_total_replies > 0) {
                    $userPost->upost_media_total_replies = $userPost->upost_media_total_replies - 1;
                    $userPost->save();
                } 

            }else{
                $userPost = UserPost::find($reply->upost_id);
                if ($userPost->upost_total_replies > 0) {
                    $userPost->upost_total_replies = $userPost->upost_total_replies - 1;
                    $userPost->save();
                }  
            }
            
            $message = Lang::get('api.comment_delete_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Get Post Type:  Message
     * @param $request
     * @param $userPost
     * @return mixed
     */
    private function postMessage($request, $userPost)
    {
        $userPost->pcat_id = $request->category;
        $userPost->upost_title = $request->subject;
        return $userPost;
    }

    /**
     * Get Post Type: Alert
     * @param $userPost
     * @return mixed
     */
    private function postAlert($userPost)
    {
        $userPost->pcat_id = 0;
        $userPost->upost_title = "";
        return $userPost;
    }

    /**
     * Get Post Type: Poll
     * @param $questions
     * @param $userPost
     * @return mixed
     */
    private function postPoll($questions, $userPost)
    {
        foreach ($questions as $q) {
            if ($q->question_id == 0 && empty($q->question_id)) {
                $postQuestion = new PostQuestion;
                $postQuestion->upost_id = $userPost->_id;
                $postQuestion->pquestion_question = $q->question;
                $postQuestion->save();
            }
        }
        return $userPost;
    }

    /**
     * Delete the Post by post_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $userPost = UserPost::find($request->post_id);
            $userPost->delete();
            PostAnswer::where('upost_id', $request->post_id)->delete();
            $message = Lang::get('api.delete_post_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Save the Post Image by post_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
                'type' => 'in:video,image',
                'file' => 'required',
                'order_id' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $file = $request->file('file');
            $input['file'] = time() . rand(2, 100) . '_' . $file->getClientOriginalName();
            return $this->uploadFile($request, $file, 'post', $request->post_id);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Delete the Post Image by image_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:post_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $postImage = PostImage::find($request->image_id);
            $userPost = UserPost::find($postImage->upost_id);
            $userPost->is_edited = 1;
            $userPost->save();
            $imagePath['image'] = public_path('upload/posts/' . $postImage->pimg_image_file);
            $imagePath['video'] = public_path('upload/posts/' . $postImage->video_file);
            return $this->deleteFile($postImage, $imagePath, 'post');
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Delete the Question by question_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteQuestion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['question_id' => 'required|exists:post_questions,_id']);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $postQuestion = PostQuestion::find($request->question_id);
            $postQuestion->delete();
            $userPost = UserPost::find($postQuestion->upost_id);
            $userPost->is_edited = 1;
            $userPost->save();
            $posts = $this->posts($postQuestion->upost_id);
            $message = Lang::get('api.quesiton_delete_message');
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Save the Post Comment/reply on Post by post_id and comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePostComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
                'comment' => 'required'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $reply = new PostReply();
            $reply->upost_id = $request->post_id;
            $reply->user_id = Auth::id();
            $reply->preply_comment = $request->comment;
            $reply->save();
            $post = UserPost::find($request->post_id);
            $post->upost_total_replies = $post->replies->where('is_image_comment','!=', 1)->count();
            $post->save();
            // Push Notification
            $userPost = UserPost::find($request->post_id);
            $userIds = PostReply::groupBy('user_id')->where('upost_id', $request->post_id)->where('user_id', '!=', Auth::id())->pluck('user_id');

            if ($post->user_id != Auth::id()) {
                $userIds[] = $post->user_id;
            }
            $threadToUsers = User::whereIn('_id', $userIds);
            $threadApproveToUsers  = $threadToUsers->whereHas('status',function ($query) {
                         $query->where('ustatus_name','approved');
            })->get();
            if ($userPost->post_type == 'message') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'message', Config::get('constant.reply_post')))->delay(now()->addSecond(1));
            } elseif ($userPost->post_type == 'alert') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'alert', Config::get('constant.reply_alert')))->delay(now()->addSecond(1));
            } elseif ($userPost->post_type == 'poll') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'poll', Config::get('constant.reply_poll')))->delay(now()->addSecond(1));

            }

            $posts = $this->posts($request->post_id);
            $message = Lang::get('api.comment_save_message');
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Get all Post Comments by post_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCommentList($id)
    {
        try {
            $replies = PostReply::with('user')->where('upost_id', $id)->where('is_image_comment','!=',1)->orderBy('created_at', 'desc')->paginate(10);
            $views = ViewPost::where('upost_id', $id)->where('user_id', Auth::id())->first();
            if ($views) {
                ViewPost::where('upost_id', $id)->where('user_id', Auth::id())->delete();
            }
            $viewPost = new ViewPost();
            $viewPost->user_id = Auth::id();
            $viewPost->upost_id = $id;
            $viewPost->view_date_time = date('Y-m-d H:i:s');
            $viewPost->save();
            $message = Lang::get('api.reply_post_message');
            $totalViews  = ViewPost::where('upost_id',$id)->count();
            $response = ['status'=>true,'message'=>$message,'data'=>$replies,'total_views'=>$totalViews];
            return response()->json($response);
            //return $this->sucessResponse(true,$message, $response);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Give the Thanks on Post by post_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postThanks(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $postThanks = PostThank::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id])->first();

            if (!is_null($postThanks)) {
                PostThank::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id])->delete();
                $post = UserPost::find($request->post_id);
                $post->upost_total_thanks = $post->thanks->count();
                $post->save();
            } else {

                $postThank = new PostThank();
                $postThank->upost_id = $request->post_id;
                $postThank->user_id = Auth::id();
                $postThank->save();
                $post = UserPost::find($request->post_id);
                $post->upost_total_thanks = $post->thanks->count();
                $post->save();
                // push notification

                if ($post->users->_id != Auth::id()) {
                    if ($post->post_type == 'message') {
                        $this->saveSinglePostNotificationMessage('message', $post, Config::get('constant.thanks_post'));
                    } elseif ($post->post_type == 'alert') {
                        $this->saveSinglePostNotificationMessage('alert', $post, Config::get('constant.thanks_alert'));
                    } elseif ($post->post_type == 'poll') {
                        $this->saveSinglePostNotificationMessage('poll', $post, Config::get('constant.thanks_poll'));
                    }
                }
            }

            $posts = $this->posts($request->post_id);
            $message = Lang::get('api.post_thank_message');
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            //return $this->exceptionResponse();
        }
    }

    /**
     * Get thanks users list that have given the thanks on post by post_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postThanksUsers($id)
    {
        try {
            $postThanks = PostThank::with('user')->where('upost_id', $id)->where('is_image_thank','!=',1)->get();
            $message = Lang::get('api.post_thank_users_message');
            return $this->sucessResponse(true, $message, $postThanks);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Give the Answer on Poll Post by post_id and question_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAnswer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
                'question_id' => 'required|exists:post_questions,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $postQuesiton = PostAnswer::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id])->first();

            if (!is_null($postQuesiton)) {

                PostAnswer::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id])->delete();
            }
            $postAnswer = new PostAnswer();
            $postAnswer->user_id = Auth::user()->id;
            $postAnswer->upost_id = $request->post_id;
            $postAnswer->pquestion_id = $request->question_id;
            $postAnswer->save();
            $posts = $this->posts($request->post_id);
            // push notification
            $post = UserPost::find($request->post_id);
            if ($post->users->_id != Auth::id()) {
                $this->saveSinglePostNotificationMessage('poll', $post, Config::get('constant.vote_poll'));
            }
            if (isset($postQuesiton->pquestion_id)) {
                if ($request->question_id !=  $postQuesiton->pquestion_id) {
                    $this->updateVotePollNotification('update_vote', $post, Config::get('constant.vote_update'));
                }
            }

            $message = Lang::get('api.post_answer_message');
            return $this->sucessResponse(true, $message, $posts);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}
