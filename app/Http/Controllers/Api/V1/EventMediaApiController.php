<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\EventImage;
use App\Models\EventReply;
use App\Models\EventThank;
use App\Models\Event;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\NotificationTrait;
use App\Traits\EventApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class EventMediaApiController extends Controller
{
    use ApiResponse, EventApiTrait, NotificationTrait;

    /**
     * Save the Event Image Comment/reply on Event Image by event_id and comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMediaComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,_id',
                'media_id' => 'required|exists:event_images,_id',
                'comment' => 'required'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

            $reply = new EventReply();
            $reply->event_id = $request->event_id;
            $reply->media_id = $request->media_id;
            $reply->user_id = Auth::id();
            $reply->preply_comment = $request->comment;
            $reply->is_image_comment = 1;
            $reply->save();
            $event_image = EventImage::find($request->media_id);
            $event_image->event_media_total_replies = $event_image->replies->count();
            $event_image->save();
            // Push Notification
            $event = Event::find($request->event_id);
            $userIds = EventReply::groupBy('user_id')->where('event_id', $request->event_id)->where('media_id',$request->media_id)->where('user_id', '!=', Auth::id())->pluck('user_id');

            if ($event->user_id != Auth::id()) {
                $userIds[] = $event->user_id;
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

            $events = $this->eventsMedia($request->event_id,$request->media_id);
            $message = Lang::get('api.comment_save_message');
            return $this->sucessResponse(true, $message, $events);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->exceptionResponse();
        }
    }

    /**
     * Get all Event Image Comments by event_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaCommentList($id,$media_id)
    {
        try {
            $replies = EventReply::with('user')->where('event_id', $id)->where('media_id', $media_id)->orderBy('created_at', 'desc')->paginate(10);
            $replies->map(function ($reply) {

                $reply['parent_id'] = $reply->event_id;
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
     * Give the Thanks on Event Image by event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanks(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,_id',
                'media_id' => 'required|exists:event_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $eventThanks = EventThank::where(['user_id' => Auth::id(), 'event_id' => $request->event_id, 'media_id'=>$request->media_id])->first();

            if (!is_null($eventThanks)) {
                EventThank::where(['user_id' => Auth::id(), 'event_id' => $request->event_id,'media_id'=>$request->media_id])->delete();
                $eventImage = EventImage::find($request->media_id);
                $eventImage->event_media_total_thanks = $eventImage->thanks->count();
                $eventImage->save();
            } else {

                $eventThank = new EventThank();
                $eventThank->event_id = $request->event_id;
                $eventThank->media_id = $request->media_id;
                $eventThank->user_id = Auth::id();
                $eventThank->is_image_thank = 1;
                $eventThank->save();
                $eventImage = EventImage::find($request->media_id);
                $eventImage->event_media_total_thanks = $eventImage->thanks->count();
                $eventImage->save();
                // push notification
                $userEvent = Event::find($request->event_id);
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

            $events = $this->eventsMedia($request->event_id,$request->media_id);
            $message = Lang::get('api.event_thank_message');
            return $this->sucessResponse(true, $message, $events);
        } catch (\Exception $e) {
            //return $this->exceptionResponse();
        }
    }

    /**
     * Get thanks users list that have given the thanks on Event Image by event_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaThanksUsers($id,$media_id)
    {
        try {
            $eventThanks = EventThank::with('user')->where('event_id', $id)->where('media_id',$media_id)->get();
            $eventThanks->map(function ($reply) {

                $reply['parent_id'] = $reply->event_id;
                return $reply;

            });
            $message = Lang::get('api.event_thank_users_message');
            return $this->sucessResponse(true, $message, $eventThanks);
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }



     /**
     * Update the event Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:event_replies,_id',
                'comment' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = EventReply::find($request->comment_id);
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
     * Delete the event Comment by comment_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:event_replies,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $reply = EventReply::find($request->comment_id);
            $reply->delete();
            $userEvent = EventImage::find($reply->media_id);
            if ($userEvent->event_media_total_replies > 0) {
                $userEvent->event_media_total_replies = $userEvent->event_media_total_replies - 1;
                $userEvent->save();
            }
            $message = Lang::get('api.comment_delete_message');
            return $this->sucessResponse(true, $message, []);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }
}

