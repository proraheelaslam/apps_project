<?php

namespace App\Jobs;

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


class EventNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use NotificationTrait, UserStatusTrait;

    protected $neighbourhoods, $eventUsers, $userEvent, $postType, $eventType, $message, $fromUser, $replyType, $actionType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userEvent, $type, $event, $actionType)
    {

        $this->postType = $type;
        $this->eventType = $event;
        $this->actionType = $actionType;
        $this->fromUser = $userEvent->users;
        $this->userEvent = $userEvent;
        $users = UserNeighborhood::with('user_detail')
            ->where('neighborhood_id', $userEvent->neighborhood_id)
            ->whereIn('user_id', $this->approvedUsers($userEvent->neighborhood_id));
        if ($this->actionType == "update") {
            $userIds = EventParticipant::where('event_id', $this->userEvent->_id)
                ->where('user_id', '!=', Auth::id())
                ->pluck('user_id');
            $users->whereIn("user_id", $userIds);

        } else {
            $users->where('user_id', '!=', $userEvent->user_id);
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
                    ->where('data', $this->userEvent->_id)
                    ->where('type', $this->eventType)
                    ->count();

                if ($notification == 0) {
                    $this->message = $this->getEventNotificationMessage($this->fromUser, $user->user_detail, $this->userEvent, $this->eventType);
                    $userNotification = new UserNotification();
                    $userNotification->to_user_id = $user->user_id;
                    $userNotification->from_user_id = $this->fromUser->_id;
                    $userNotification->data = $this->userEvent->_id;
                    $userNotification->title = $this->message->name;
                    $userNotification->message = $this->message->message;
                    $userNotification->type = $this->eventType;
                    $userNotification->unread_status = 0;
                    $userNotification->is_seen = 0;
                    $userNotification->save();
                    $settingKey = '';
                    switch ($this->eventType) {
                        case Config::get('constant.new_event'):
                            $settingKey = 'event_notification';
                            break;
                        case Config::get('constant.event_update'):
                            $settingKey = 'event_notification';
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
