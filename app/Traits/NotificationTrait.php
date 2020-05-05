<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */

namespace App\Traits;


use App\Models\AppNotification;
use App\Models\AppSetting;
use App\Models\PostAnswer;
use App\Models\User;
use App\Models\UserNeighborhood;
use App\Models\UserNotification;
use App\Models\UserPost;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


/**
 * Trait NotificationTrait
 * @package App\Traits
 */
trait NotificationTrait
{
    public function getPostNotificationMessage($fromUser, $toUser, $post, $type)
    {
        $appNotification = AppNotification::where('key', $type)->first();
        $poster_name = $post->users->full_name;
        if ($toUser->_id == $post->user_id) {
            $poster_name = "your";
        }
        switch ($type) {
            case Config::get('constant.new_post'):
                $appNotification->message = str_replace(['{username}', '{post_title}'], [$fromUser->full_name, $post->upost_title], $appNotification->message);
                break;
            case Config::get('constant.post_updated'):
                $appNotification->message = str_replace(['{username}', '{post_title}'], [$fromUser->full_name, $post->upost_title], $appNotification->message);
                break;
            case Config::get('constant.new_poll'):
                $appNotification->message = str_replace(['{username}', '{poll_title}'], [$fromUser->full_name, $post->upost_description], $appNotification->message);
                break;
            case Config::get('constant.poll_updated'):
                $appNotification->message = str_replace(['{username}', '{poll_title}'], [$fromUser->full_name, $post->upost_description], $appNotification->message);
                break;
            case Config::get('constant.new_alert'):
                $appNotification->message = str_replace(['{username}', '{alert_title}'], [$fromUser->full_name, $post->upost_description], $appNotification->message);
                break;

            case Config::get('constant.alert_updated'):
                $appNotification->message = str_replace(['{username}', '{alert_title}'], [$fromUser->full_name, $post->upost_description], $appNotification->message);
                break;
            case Config::get('constant.reply_post'):
                $appNotification->message = str_replace(['{username}', '{postername}', '{post_title}'], [$fromUser->full_name, $poster_name, $post->upost_title], $appNotification->message);
                break;
            case Config::get('constant.reply_media'):
                $appNotification->message = str_replace(['{username}', '{postername}', '{post_title}'], [$fromUser->full_name, $poster_name, $post->upost_title], $appNotification->message);
                break;
            case Config::get('constant.reply_alert'):
                $appNotification->message = str_replace(['{username}', '{postername}', '{alert_title}'], [$fromUser->full_name, $poster_name, $post->upost_description], $appNotification->message);
                break;
            case Config::get('constant.reply_poll'):
                $appNotification->message = str_replace(['{username}', '{postername}', '{poll_title}'], [$fromUser->full_name, $poster_name, $post->upost_description], $appNotification->message);
                break;
        }
        return $appNotification;
    }

    /**
     * @param $fromUser
     * @param $toUser
     * @param $classified
     * @param $type
     * @return mixed
     */
    public function getClassifiedNotificationMessage($fromUser, $toUser, $classified, $type)
    {
        $appNotification = AppNotification::where('key', $type)->first();
        $user = Auth::user();
        $gender = $classified->users->gender->gender_name;
        $name = 'his';
        if ($gender == 'Female') {
            $name = 'her';
        }
        switch ($type) {
            case Config::get('constant.new_classified'):
                $appNotification->message = str_replace(['{username}'], [$fromUser->full_name], $appNotification->message);
                break;
            case Config::get('constant.classified_updated'):
                $appNotification->message = str_replace(['{username}', '{classified_title}', '{name}'], [$fromUser->full_name, $classified->classified_title, $name], $appNotification->message);
                break;
        }
        return $appNotification;
    }

    /**
     * @param $fromUser
     * @param $toUser
     * @param $neighbourhood
     * @param $type
     * @return mixed
     */
    public function getNeighbourhoodNotificationMessage($fromUser, $toUser, $neighbourhood, $type)
    {
        $appNotification = AppNotification::where('key', $type)->first();

        switch ($type) {
            case Config::get('constant.join_neighbourhood'):
                $appNotification->message = str_replace(['{username}', '{neighbourhood_name}'], [$fromUser->full_name, $neighbourhood->neighborhood_name], $appNotification->message);
                break;
        }
        return $appNotification;
    }

    /**
     * @param $fromUser
     * @param $toUser
     * @param $event
     * @param $type
     * @return mixed
     */
    public function getEventNotificationMessage($fromUser, $toUser, $event, $type)
    {
        $appNotification = AppNotification::where('key', $type)->first();
        $gender = $event->users->gender->gender_name;
        $name = 'his';
        if ($gender == 'Female') {
            $name = 'her';
        }
        switch ($type) {
            case Config::get('constant.new_event'):
                $appNotification->message = str_replace(['{username}', '{event_title}'], [$fromUser->full_name, $event->title], $appNotification->message);
                break;
            case Config::get('constant.event_update'):
                $appNotification->message = str_replace(['{username}', '{name}', '{event_title}'], [$fromUser->full_name, $name, $event->title], $appNotification->message);
                break;
        }
        return $appNotification;
    }

    /**
     * @param $fromUser
     * @param $toUser
     * @param $business
     * @param $type
     * @return mixed
     */
    public function getBusinessNotificationMessage($fromUser, $toUser, $business, $type)
    {
        $appNotification = AppNotification::where('key', $type)->first();

        switch ($type) {
            case Config::get('constant.new_business'):
                $appNotification->message = str_replace(['{businessname}', '{username}'], [$business->business_name, $fromUser->full_name], $appNotification->message);
                break;
            case Config::get('constant.business_update'):
                $appNotification->message = str_replace(['{businessname}'], [$business->business_name], $appNotification->message);
                break;
            case Config::get('constant.business_recommended'):
                $appNotification->message = str_replace(['{username}', '{businessname}'], [$fromUser->full_name, $business->business_name], $appNotification->message);
                break;
        }
        return $appNotification;
    }

    /**
     * @param $type
     * @param $post
     * @param $event
     */
    public function saveSinglePostNotificationMessage($type, $post, $event)
    {
        $appNotification = AppNotification::where('key', $event)->first();
        if ($type == 'message') {
            $message = str_replace(['{username}', '{post_title}'], [Auth::user()->full_name, $post->upost_description], $appNotification->message);

        } else if ($type == 'alert') {
            $message = str_replace(['{username}', '{alert_title}'], [Auth::user()->full_name, $post->upost_description], $appNotification->message);

        } else if ($type == 'poll') {
            $message = str_replace(['{username}', '{poll_title}'], [Auth::user()->full_name, $post->upost_description], $appNotification->message);

        }
        $userNotification = new UserNotification();
        $userNotification->to_user_id = $post->user_id;
        $userNotification->from_user_id = Auth::id();
        $userNotification->data = $post->_id;
        $userNotification->title = $appNotification->name;
        $userNotification->message = $message;
        $userNotification->type = $event;
        $userNotification->unread_status = 0;
        $userNotification->is_seen = 0;
        $userNotification->save();
        $settingKey = '';
        switch ($event) {
            case Config::get('constant.thanks_post'):
                $settingKey = 'post_notification';
                break;
            case Config::get('constant.thanks_media'):
                $settingKey = 'post_notification';
                break;
            case Config::get('constant.thanks_alert'):
                $settingKey = 'alert_notification';
                break;
            case Config::get('constant.thanks_poll'):
                $settingKey = 'poll_notification';
                break;
            case Config::get('constant.vote_poll'):
                $settingKey = 'poll_notification';
                break;
        }
        $isettingOn = $this->getUserPostSetting($post->users->_id, $settingKey);
        if ($isettingOn) {
            $userNotification->sendNotifocation($userNotification);
        }
        return 'true';
    }

    /**
     * @param $type
     * @param $post
     * @param $event
     */
    public function updateVotePollNotification($type, $post, $event)
    {

        $appNotification = AppNotification::where('key', $event)->first();
        $message = str_replace(['{username}', '{poll_title}'], [Auth::user()->full_name, $post->upost_description], $appNotification->message);
        $votedUsers = PostAnswer::where('upost_id', $post->_id)
            ->where('user_id', '!=', Auth::id())
            ->pluck('user_id');
        $users = User::whereIn('_id', $votedUsers)->get();
        foreach ($users as $user) {
            $userNotification = new UserNotification();
            $userNotification->to_user_id = $post->_id;
            $userNotification->from_user_id = Auth::id();
            $userNotification->title = $appNotification->name;
            $userNotification->data = $post->_id;
            $userNotification->message = $message;
            $userNotification->type = $event;
            $userNotification->unread_status = 0;
            $userNotification->is_seen = 0;
            $userNotification->save();
            $settingKey = '';
            switch ($event) {
                case Config::get('constant.update_vote'):
                    $settingKey = 'post_notification';
                    break;
            }
            $isettingOn = $this->getUserPostSetting($user->_id, $settingKey);
            if ($isettingOn) {
                $userNotification->sendNotifocation($userNotification);
            }
        }
        //
    }

    /**
     * @param $userId
     * @param $type
     * @return bool
     */
    public function getUserPostSetting($userId, $type)
    {
        $setting = AppSetting::where('app_setting_key', $type)->first();
        if ($setting) {
            $userSetting = UserSetting::where(['user_id' => $userId, 'app_setting_id' => $setting->_id])->first();
            if ($userSetting) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    /******Notification from Admin*********/

    public function adminNotification($user, $event, $type)
    {
        $appNotification = AppNotification::where('key', $event)->first();

        if ($type == 'update_neighbourhood') {
            $message = str_replace(['{neighbourhood_title}', '{username}'], [$user->neighborhood_name, Auth::guard('admin')->user()->name], $appNotification->message);

            $neighborhood = UserNeighborhood::where('neighborhood_id', $user->_id)->get();
            foreach ($neighborhood as $userNeighbourhoods) {
                $userNotification = new UserNotification();
                $userNotification->to_user_id = $userNeighbourhoods->user_id;
                $userNotification->from_user_id = Auth::id();
                $userNotification->title = $appNotification->name;
                $userNotification->message = $message;
                $userNotification->data = '';
                $userNotification->type = $event;
                $userNotification->unread_status = 0;
                $userNotification->is_send = 1;
                $userNotification->is_seen = 0;
                $userNotification->save();
                $settingKey = '';

                switch ($event) {

                    case  Config::get('constant.neighbourhood_declined'):
                        $settingKey = 'neighbourhood_status_notification';
                        break;
                    case Config::get('constant.neighbourhood_approved'):
                        $settingKey = 'neighbourhood_status_notification';
                        break;
                    case Config::get('constant.neighbourhood_updated'):
                        $settingKey = 'neighbourhood_status_notification';
                        break;

                }
                $isettingOn = $this->getUserPostSetting($userNeighbourhoods->user_id, $settingKey);
                if ($isettingOn) {
                    $userNotification->sendNotifocation($userNotification);
                }


            }

        } else {


            $message = str_replace(['{username}'], [$user->full_name], $appNotification->message);
            $userNotification = new UserNotification();
            $userNotification->to_user_id = $user->_id;
            $userNotification->from_user_id = Auth::id();
            $userNotification->title = $appNotification->name;
            $userNotification->message = $message;
            $userNotification->data = '';
            $userNotification->type = $event;
            $userNotification->unread_status = 0;
            $userNotification->is_seen = 0;
            $userNotification->save();

            switch ($event) {
                case Config::get('constant.updates_user_info'):
                    $settingKey = 'admin_update_information_notification';
                    break;
                case Config::get('constant.approves_user_account'):
                    $settingKey = 'admin_user_account_status_notification';
                    break;
                case  Config::get('constant.disapproves_user_account'):
                    $settingKey = 'admin_user_account_status_notification';
                    break;
                case  Config::get('constant.neighbourhood_declined'):
                    $settingKey = 'neighbourhood_status_notification';
                    break;
                case Config::get('constant.neighbourhood_approved'):
                    $settingKey = 'neighbourhood_status_notification';
                    break;
                case Config::get('constant.neighbourhood_updated'):
                    $settingKey = 'neighbourhood_status_notification';
                    break;

                case Config::get('constant.rejects_document'):
                    $settingKey = 'admin_document_notification';
                    break;
                case Config::get('constant.approves_document'):
                    $settingKey = 'admin_document_notification';
                    break;
                case  Config::get('constant.deactivates_user_account'):
                    $settingKey = 'admin_user_account_status_notification';
                    break;

            }
            $isettingOn = $this->getUserPostSetting($user->_id, $settingKey);

            if ($isettingOn) {
                $userNotification->sendNotifocation($userNotification);
            }


        }

    }

    public function chatNotification($thread, $type)
    {
        $user = User::find($thread->cthread_from_user_id);
        $toUser = User::find($thread->cthread_to_user_id);
        $settingKey = 'private_message_notification';
        $isettingOn = $this->getUserPostSetting($toUser->_id, $settingKey);
        if ($isettingOn) {
            if (isset($user->is_live) || $user->is_live == false) {
                $userNotification = new UserNotification();
                $userNotification->to_user_id = $thread->cthread_to_user_id;
                $userNotification->from_user_id = $thread->cthread_from_user_id;
                $userNotification->title = $user->full_name;
                $userNotification->message = $thread->cthread_message;
                $userNotification->data = '';
                $userNotification->type = $type;
                $userNotification->unread_status = 0;
                $userNotification->is_seen = 0;

                if(isset($thread->time)){
                   $userNotification->time = $thread->time; 
                }
                
                $userNotification->save();
                $userNotification->sendNotifocation($userNotification);
            }

        }

    }
}