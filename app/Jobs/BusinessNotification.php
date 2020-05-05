<?php

namespace App\Jobs;

use App\Models\BusinessRecommendations;
use App\Models\EventParticipant;
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
use Illuminate\Support\Facades\Log;

class BusinessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationTrait, UserStatusTrait;

    protected $neighbourhoods, $eventUsers, $userBusiness, $postType, $eventType, $message, $fromUser, $replyType, $actionType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userBusiness, $type, $event, $actionType)
    {
        $this->postType = $type;
        $this->eventType = $event;
        $this->actionType = $actionType;
        $this->userBusiness = $userBusiness;
        $users = UserNeighborhood::with('user_detail')
            ->where('neighborhood_id', $userBusiness->neighborhood_id)
            ->whereIn('user_id', $this->approvedUsers($userBusiness->neighborhood_id))
            ->where('user_id', '!=', $userBusiness->user_id);
        if ($this->actionType == 'recommended') {
            $recommededUsers = BusinessRecommendations::where('business_id', $userBusiness->_id)->pluck('user_id');
            $recommededUsers[] = Auth::id();
            $users->whereNotIn('user_id', $recommededUsers);
            $this->fromUser = Auth::user();
        } else {
            $this->fromUser = $userBusiness->users;
        }
        $this->neighbourhoods = $users->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            foreach ($this->neighbourhoods as $user) {

                $notification = UserNotification::where('to_user_id', $user->user_id)
                    ->where('data', $this->userBusiness->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getBusinessNotificationMessage($this->fromUser, $user->user_detail, $this->userBusiness, $this->eventType);
                    $userNotification = new UserNotification();
                    $userNotification->to_user_id = $user->user_id;
                    $userNotification->from_user_id = $this->fromUser->_id;
                    $userNotification->data = $this->userBusiness->_id;
                    $userNotification->title = $this->message->name;
                    $userNotification->message = $this->message->message;
                    $userNotification->type = $this->eventType;
                    $userNotification->unread_status = 0;
                    $userNotification->is_seen = 0;
                    $userNotification->save();
                    $settingKey = '';
                    switch ($this->eventType) {
                        case Config::get('constant.new_business'):
                            $settingKey = 'business_notification';
                            break;
                        case Config::get('constant.business_update'):
                            $settingKey = 'business_notification';
                            break;
                        case Config::get('constant.business_recommended'):
                            $settingKey = 'business_notification';
                            break;
                    }

                    $isettingOn = $this->getUserPostSetting($user->user_id, $settingKey);
                    if ($isettingOn) {
                        $userNotification->sendNotifocation($userNotification);
                    }
                }

            }

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return true;
        }
    }
}
