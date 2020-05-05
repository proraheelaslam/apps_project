<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\ClassifiedImage;
use App\Models\ClassifiedReply;
use App\Models\ClassifiedThank;
use App\Models\Classified;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\NotificationTrait;
use App\Traits\ClassifiedApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ClassifiedMediaApiController extends Controller
{
    use ApiResponse, ClassifiedApiTrait, NotificationTrait;

    /**
     * Save the Classified Image Comment/reply on Classified by classified_id and comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMediaComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'classified_id' => 'required|exists:classifieds,_id',
                'media_id' => 'required|exists:classified_images,_id',
                'comment' => 'required'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $reply = new ClassifiedReply();
            $reply->classified_id = $request->classified_id;
            $reply->media_id = $request->media_id;
            $reply->user_id = Auth::id();
            $reply->preply_comment = $request->comment;
            $reply->is_image_comment = 1;
            $reply->save();
            $classified_image = ClassifiedImage::find($request->media_id);
            $classified_image->classified_media_total_replies = $classified_image->replies->count();
            $classified_image->save();
            // Push Notification
            $classified = Classified::find($request->classified_id);
            $userIds = ClassifiedReply::groupBy('user_id')->where('classified_id', $request->classified_id)->where('media_id',$request->media_id)->where('user_id', '!=', Auth::id())->pluck('user_id');

            if ($classified->user_id != Auth::id()) {
                $userIds[] = $classified->user_id;
            }
            $threadToUsers = User::whereIn('_id', $userIds);
            $threadApproveToUsers  = $threadToUsers->whereHas('status',function ($query) {
                         $query->where('ustatus_name','approved');
            })->get();


            /*if ($event->post_type == 'message') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'message', Config::get('constant.reply_post')))->delay(now()->addSecond(1));
            } elseif ($userPost->post_type == 'alert') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'alert', Config::get('constant.reply_alert')))->delay(now()->addSecond(1));
            } elseif ($userPost->post_type == 'poll') {
                dispatch(new PostCommentNotification($userPost, $threadApproveToUsers, 'poll', Config::get('constant.reply_poll')))->delay(now()->addSecond(1));

            }*/

            $classifieds = $this->classifiedMedia($request->classified_id,$request->media_id);
            $message = Lang::get('api.comment_save_message');
            return $this->sucessResponse(true, $message, $classifieds);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Get all Classified image Comments by classified_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaCommentList($id,$media_id)
    {
        try {
            $replies = ClassifiedReply::with('user')->where('classified_id', $id)->where('media_id', $media_id)->orderBy('created_at', 'desc')->paginate(10);
            $replies->map(function ($reply) {

                $reply['parent_id'] = $reply->classified_id;
                return $reply;

            });

            $message = Lang::get('api.reply_post_message');
            $response = ['status'=>true,'message'=>$message,'data'=>$replies];
            return response()->json($response);
            //return $this->sucessResponse(true,$message, $response);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    /**
     * Give the Thanks on Classified Image by classified_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanks(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'classified_id' => 'required|exists:classifieds,_id',
                'media_id' => 'required|exists:classified_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $classifiedThanks = ClassifiedThank::where(['user_id' => Auth::id(), 'classified_id' => $request->classified_id, 'media_id'=>$request->media_id])->first();

            if (!is_null($classifiedThanks)) {
                ClassifiedThank::where(['user_id' => Auth::id(), 'classified_id' => $request->classified_id,'media_id'=>$request->media_id])->delete();
                $classifiedImage = ClassifiedImage::find($request->media_id);
                $classifiedImage->classified_media_total_thanks = $classifiedImage->thanks->count();
                $classifiedImage->save();
            } else {

                $classifiedThank = new ClassifiedThank();
                $classifiedThank->classified_id = $request->classified_id;
                $classifiedThank->media_id = $request->media_id;
                $classifiedThank->user_id = Auth::id();
                $classifiedThank->is_image_thank = 1;
                $classifiedThank->save();
                $classifiedImage = ClassifiedImage::find($request->media_id);
                $classifiedImage->classified_media_total_thanks = $classifiedImage->thanks->count();
                $classifiedImage->save();
                // push notification
                $userClassified = Classified::find($request->classified_id);
                /*if ($userEvent->users->_id != Auth::id()) {
                    if ($userPost->post_type == 'message') {
                        $this->saveSinglePostNotificationMessage('message', $userPost, Config::get('constant.thanks_post'));
                    } elseif ($userPost->post_type == 'alert') {
                        $this->saveSinglePostNotificationMessage('alert', $userPost, Config::get('constant.thanks_alert'));
                    } elseif ($userPost->post_type == 'poll') {
                        $this->saveSinglePostNotificationMessage('poll', $userPost, Config::get('constant.thanks_poll'));
                    }
                }*/
            }

            $classifieds = $this->classifiedMedia($request->classified_id,$request->media_id);
            $message = Lang::get('api.classified_thank_message');
            return $this->sucessResponse(true, $message, $classifieds);
        } catch (\Exception $e) {
            //return $this->exceptionResponse();
        }
    }

    /**
     * Get thanks users list that have given the thanks on Classified Image by classified_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanksUsers($id,$media_id)
    {
        try {
            $classifiedThanks = ClassifiedThank::with('user')->where('classified_id', $id)->where('media_id',$media_id)->get();
            $classifiedThanks->map(function ($reply) {

                $reply['parent_id'] = $reply->classified_id;
                return $reply;

            });


            $message = Lang::get('api.classified_thank_users_message');
            return $this->sucessResponse(true, $message, $classifiedThanks);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }



     /**
     * Update the classified Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:classified_replies,_id',
                'comment' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = ClassifiedReply::find($request->comment_id);
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
     * Delete the classified Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:classified_replies,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = ClassifiedReply::find($request->comment_id);
            $reply->delete();
            $userClassified = ClassifiedImage::find($reply->media_id);
            if ($userClassified->classified_media_total_replies > 0) {
                $userClassified->classified_media_total_replies = $userClassified->classified_media_total_replies - 1;
                $userClassified->save();
            }
            $message = Lang::get('api.comment_delete_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }
}

