<?php

namespace App\Jobs;

use App\Models\UserNeighborhood;
use App\Models\UserNotification;
use App\Traits\MailTrait;
use App\Traits\NotificationTrait;
use App\Traits\UserStatusTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class PostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MailTrait;
    use NotificationTrait, UserStatusTrait;

    protected $posts, $userPost,$postType, $eventType, $message, $fromUser, $replyType, $commentThreadUsers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userPost,$type,$event)
    {
        $this->postType = $type;
        $this->eventType = $event;
        $this->userPost = $userPost;
        $this->posts  = UserNeighborhood::with('user_detail')
            ->where('user_id','!=',$this->userPost->user_id)
            ->where('neighborhood_id',$userPost->neighborhood_id)
            ->whereIn('user_id',$this->approvedUsers($userPost->neighborhood_id))
            ->get();
        $this->fromUser = $this->userPost->users;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            foreach ($this->posts as $user) {
                $notification = UserNotification::where('to_user_id', $user->user_id)
                    ->where('data', $this->userPost->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getPostNotificationMessage($this->fromUser, $user->user_detail, $this->userPost, $this->eventType);
                    $userNotification = new UserNotification;
                    $userNotification->to_user_id = $user->user_id;
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
                        case Config::get('constant.new_post'):
                            $settingKey = 'post_notification';
                            break;
                        case Config::get('constant.post_updated'):
                            $settingKey = 'post_notification';
                            break;
                        case Config::get('constant.new_alert'):
                            $settingKey = 'alert_notification';
                            break;

                        case Config::get('constant.alert_updated'):
                            $settingKey = 'alert_notification';
                            break;
                        case Config::get('constant.new_poll'):
                            $settingKey = 'poll_notification';
                            break;
                        case Config::get('constant.poll_updated'):
                            $settingKey = 'poll_notification';
                            break;
                    }
                    $isettingOn = $this->getUserPostSetting($user->user_id,$settingKey);
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
