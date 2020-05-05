<?php

namespace App\Jobs;

use App\Models\UserNotification;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PostCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationTrait;

    protected $posts, $userPost,$postType, $eventType, $message, $fromUser, $replyType, $postCommentThreadUsers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userPost,$toThreadUsers,$type,$event)
    {
        $this->postType = $type;
        $this->eventType = $event;
        $this->userPost = $userPost;
        $this->postCommentThreadUsers = $toThreadUsers;
        $this->fromUser = Auth::user();

    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            foreach ($this->postCommentThreadUsers as $user) {

                $notification = UserNotification::where('to_user_id', $user->user_id)
                    ->where('data', $this->userPost->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getPostNotificationMessage($this->fromUser, $user, $this->userPost, $this->eventType);
                    $userNotification = new UserNotification();
                    $userNotification->to_user_id = $user->_id;
                    $userNotification->from_user_id = $this->fromUser->_id;
                    $userNotification->data = $this->userPost->_id;
                    $userNotification->title = $this->message->name;
                    $userNotification->message = $this->message->message;
                    $userNotification->type = $this->eventType;
                    $userNotification->unread_status = 0;
                    $userNotification->is_seen = 0;
                    $userNotification->save();
                    $settingKey = '';
                    switch ($this->eventType) {
                        case Config::get('constant.reply_post'):
                            $settingKey = 'post_notification';
                            break;
                        case Config::get('constant.reply_media'):
                            $settingKey = 'post_notification';
                            break;
                        case Config::get('constant.reply_alert'):
                            $settingKey = 'alert_notification';
                            break;
                        case Config::get('constant.reply_poll'):
                            $settingKey = 'poll_notification';
                            break;
                    }
                    $isettingOn = $this->getUserPostSetting($user->_id,$settingKey);

                    if ($isettingOn) {
                        $userNotification->sendNotifocation($userNotification);
                    }
                }



            }
        }catch (\Exception $e) {
            return true;
        }
    }
}
