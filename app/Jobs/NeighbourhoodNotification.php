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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class NeighbourhoodNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationTrait, UserStatusTrait;

    protected $neighbourhoods, $userNeighbourhoods,$postType, $eventType, $message, $fromUser, $replyType;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userNeighbourhoods,$type,$event)
    {
        $this->postType = $type;
        $this->eventType = $event;
        $this->userNeighbourhoods = $userNeighbourhoods;
        $this->neighbourhoods = UserNeighborhood::with('user_detail')
            ->where('user_id', '!=', Auth::id())
            ->where('neighborhood_id', $userNeighbourhoods->_id)
            ->whereIn('user_id',$this->approvedUsers($userNeighbourhoods->_id))
            ->get();
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
            foreach ($this->neighbourhoods as $user) {

                $notification = UserNotification::where('to_user_id', $user->user_id)
                    ->where('data', $this->userNeighbourhoods->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getNeighbourhoodNotificationMessage($this->fromUser, $user->user_detail, $this->userNeighbourhoods, $this->eventType);
                    $userNotification = new UserNotification();
                    $userNotification->to_user_id = $user->user_id;
                    $userNotification->from_user_id = $this->fromUser->_id;
                    $userNotification->data = $this->userNeighbourhoods->_id;
                    $userNotification->title = $this->message->name;
                    $userNotification->message = $this->message->message;
                    $userNotification->type = $this->eventType;
                    $userNotification->unread_status = 0;
                    $userNotification->is_seen = 0;
                    $userNotification->save();
                    $settingKey = '';
                    switch ($this->eventType) {
                        case Config::get('constant.join_neighbourhood'):
                            $settingKey = 'join_neighbourhood_notification';
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
