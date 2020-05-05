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
use App\Models\UserNotification;
class PostMediaApiController extends Controller
{
    use ApiResponse, PostApiTrait, NotificationTrait;

    /**
     * Save the Post Image Comment/reply on Post by post_id and comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMediaComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
                'media_id' => 'required|exists:post_images,_id',
                'comment' => 'required'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $reply = new PostReply();
            $reply->upost_id = $request->post_id;
            $reply->media_id = $request->media_id;
            $reply->user_id = Auth::id();
            $reply->preply_comment = $request->comment;
            $reply->is_image_comment = 1;
            $reply->save();
            $post_image = PostImage::find($request->media_id);
            $post_image->upost_media_total_replies = $post_image->replies->count();
            $post_image->save();
            // Push Notification
            $userPost = UserPost::find($request->post_id);
            $userIds = PostReply::groupBy('user_id')->where('upost_id', $request->post_id)->where('media_id',$request->media_id)->where('user_id', '!=', Auth::id())->pluck('user_id');

            if ($userPost->user_id != Auth::id()) {
                $userIds[] = $userPost->user_id;
            }
            $threadToUsers = User::whereIn('_id', $userIds);
            $threadApproveToUsers  = $threadToUsers->whereHas('status',function ($query) {
                         $query->where('ustatus_name','approved');
            })->get();
            if ($userPost->post_type == 'message') {

            dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'message', Config::get('constant.reply_media')))->delay(now()->addSecond(1));

            } elseif ($userPost->post_type == 'alert') {
               dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'alert', Config::get('constant.reply_alert')))->delay(now()->addSecond(1));
            } elseif ($userPost->post_type == 'poll') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'poll', Config::get('constant.reply_poll')))->delay(now()->addSecond(1));

            }
            $posts = $this->postsMedia($request->post_id,$request->media_id);
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
    public function mediaCommentList($id,$media_id)
    {
        try {
            $replies = PostReply::with('user')->where('upost_id', $id)->where('media_id', $media_id)->orderBy('created_at', 'desc')->paginate(10);

            $replies->map(function ($reply) {

                $reply['parent_id'] = $reply->upost_id;
                return $reply;

            });

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
    public function mediaThanks(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:user_posts,_id',
                'media_id' => 'required|exists:post_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $postThanks = PostThank::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id, 'media_id'=>$request->media_id])->first();

            if (!is_null($postThanks)) {
                PostThank::where(['user_id' => Auth::id(), 'upost_id' => $request->post_id,'media_id'=>$request->media_id])->delete();
                $postImage = PostImage::find($request->media_id);
                $postImage->upost_media_total_thanks = $postImage->thanks->count();
                $postImage->save();
            } else {

                $postThank = new PostThank();
                $postThank->upost_id = $request->post_id;
                $postThank->media_id = $request->media_id;
                $postThank->user_id = Auth::id();
                $postThank->is_image_thank = 1;
                $postThank->save();
                $postImage = PostImage::find($request->media_id);
                $postImage->upost_media_total_thanks = $postImage->thanks->count();
                $postImage->save();
                // push notification
                $userPost = UserPost::find($request->post_id);
                if ($userPost->users->_id != Auth::id()) {
                    if ($userPost->post_type == 'message') {
                        $this->saveSinglePostNotificationMessage('message', $userPost, Config::get('constant.thanks_media'));
                    } elseif ($userPost->post_type == 'alert') {
                        $this->saveSinglePostNotificationMessage('alert', $userPost, Config::get('constant.thanks_alert'));
                    } elseif ($userPost->post_type == 'poll') {
                        $this->saveSinglePostNotificationMessage('poll', $userPost, Config::get('constant.thanks_poll'));
                    }
                }
            }

            $posts = $this->postsMedia($request->post_id,$request->media_id);
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
    public function mediaThanksUsers($id,$media_id)
    {
        try {
            $postThanks = PostThank::with('user')->where('upost_id', $id)->where('media_id',$media_id)->get();
            $postThanks->map(function ($reply) {

                $reply['parent_id'] = $reply->upost_id;
                return $reply;

            });

            $message = Lang::get('api.post_thank_users_message');
            return $this->sucessResponse(true, $message, $postThanks);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}

