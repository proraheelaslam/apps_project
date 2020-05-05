<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\BusinessImage;
use App\Models\BusinessReply;
use App\Models\BusinessThank;
use App\Models\Business;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\NotificationTrait;
use App\Traits\BusinessApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class BusinessMediaApiController extends Controller
{
    use ApiResponse, BusinessApiTrait, NotificationTrait;

    /**
     * Save the Business Image Comment/reply on Business Image by business_id and comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMediaComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|exists:businesses,_id',
                'media_id' => 'required|exists:business_images,_id',
                'comment' => 'required'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $reply = new BusinessReply();
            $reply->business_id = $request->business_id;
            $reply->media_id = $request->media_id;
            $reply->user_id = Auth::id();
            $reply->preply_comment = $request->comment;
            $reply->is_image_comment = 1;
            $reply->save();
            $business_image = BusinessImage::find($request->media_id);
            $business_image->business_media_total_replies = $business_image->replies->count();
            $business_image->save();
            // Push Notification
            $business = Business::find($request->business_id);
            $userIds = BusinessReply::groupBy('user_id')->where('business_id', $request->business_id)->where('media_id',$request->media_id)->where('user_id', '!=', Auth::id())->pluck('user_id');

            if ($business->user_id != Auth::id()) {
                $userIds[] = $business->user_id;
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

            $businesses = $this->businessMedia($request->business_id,$request->media_id);
            $message = Lang::get('api.comment_save_message');
            return $this->sucessResponse(true, $message, $businesses);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Get all Business Image Comments by business_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaCommentList($id,$media_id)
    {
        try {
            $replies = BusinessReply::with('user')->where('business_id', $id)->where('media_id', $media_id)->orderBy('created_at', 'desc')->paginate(10);


            $replies->map(function ($reply) {

                $reply['parent_id'] = $reply->business_id;
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
     * Give the Thanks on Business by business_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanks(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|exists:businesses,_id',
                'media_id' => 'required|exists:business_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $businessThanks = BusinessThank::where(['user_id' => Auth::id(), 'business_id' => $request->business_id, 'media_id'=>$request->media_id])->first();

            if (!is_null($businessThanks)) {
                BusinessThank::where(['user_id' => Auth::id(), 'business_id' => $request->business_id,'media_id'=>$request->media_id])->delete();
                $businessImage = BusinessImage::find($request->media_id);
                $businessImage->business_media_total_thanks = $businessImage->thanks->count();
                $businessImage->save();
            } else {

                $businessThank = new BusinessThank();
                $businessThank->business_id = $request->business_id;
                $businessThank->media_id = $request->media_id;
                $businessThank->user_id = Auth::id();
                $businessThank->is_image_thank = 1;
                $businessThank->save();
                $businessImage = BusinessImage::find($request->media_id);
                $businessImage->business_media_total_thanks = $businessImage->thanks->count();
                $businessImage->save();
                // push notification
                $userBusiness = Business::find($request->business_id);
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

            $businesses = $this->businessMedia($request->business_id,$request->media_id);
            $message = Lang::get('api.business_thank_message');
            return $this->sucessResponse(true, $message, $businesses);
        } catch (\Exception $e) {
            //return $this->exceptionResponse();
        }
    }

    /**
     * Get thanks users list that have given the thanks on business by business_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanksUsers($id,$media_id)
    {
        try {
            $businessThanks = BusinessThank::with('user')->where('business_id', $id)->where('media_id',$media_id)->get();
            $businessThanks->map(function ($reply) {

                $reply['parent_id'] = $reply->business_id;
                return $reply;

            });

            $message = Lang::get('api.business_thank_users_message');
            return $this->sucessResponse(true, $message, $businessThanks);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }



     /**
     * Update the business Image Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:business_replies,_id',
                'comment' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = BusinessReply::find($request->comment_id);
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
     * Delete the Business Image Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:business_replies,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = BusinessReply::find($request->comment_id);
            $reply->delete();
            $userBusiness = BusinessImage::find($reply->media_id);
            if ($userBusiness->business_media_total_replies > 0) {
                $userBusiness->business_media_total_replies = $userBusiness->business_media_total_replies - 1;
                $userBusiness->save();
            }
            $message = Lang::get('api.comment_delete_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }
}

