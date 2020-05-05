<?php

namespace App\Jobs;

use App\Models\UserNeighborhood;
use App\Models\UserNotification;
use App\Traits\NotificationTrait;
use App\Traits\UserStatusTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class ClassifiedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationTrait, UserStatusTrait;

    protected $classifieds, $userClassified,$postType, $eventType, $message, $fromUser, $replyType, $commentThreadUsers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userClassified,$type,$event)
    {
        $this->postType = $type;
        $this->eventType = $event;
        $this->userClassified = $userClassified;
        $this->classifieds  = UserNeighborhood::with('user_detail')
            ->where('user_id','!=',$this->userClassified->user_id)
            ->where('neighborhood_id',$userClassified->neighborhood_id)
            ->whereIn('user_id',$this->approvedUsers($userClassified->neighborhood_id))
            ->get();
        $this->fromUser = $this->userClassified->users;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            foreach ($this->classifieds as $user) {

                $notification = UserNotification::where('to_user_id', $user->user_id)
                    ->where('data', $this->userClassified->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getClassifiedNotificationMessage($this->fromUser, $user->user_detail, $this->userClassified, $this->eventType);
                    $userNotification = new UserNotification;
                    $userNotification->to_user_id = $user->user_id;
                    $userNotification->from_user_id = $this->fromUser->_id;
                    $userNotification->data = $this->userClassified->_id;
                    $userNotification->title =  $this->message->name;
                    $userNotification->message = $this->message->message;
                    $userNotification->type = $this->eventType;
                    $userNotification->unread_status = 0;
                    $userNotification->is_seen = 0;
                    $userNotification->save();
                    $settingKey = '';
                    switch ($this->eventType) {
                        case Config::get('constant.new_classified'):
                            $settingKey = 'classified_notification';
                            break;
                        case Config::get('constant.classified_updated'):
                            $settingKey = 'classified_notification';
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
