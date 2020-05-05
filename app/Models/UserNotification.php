<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Eloquent\Model;


class UserNotification extends Model
{
    //
    protected $table = 'user_notifications';
    protected $appends = ['notification_time'];

    protected $fillable = [
        'user_id',
        'data',
        'from_user_id',
        'to_user_id',
        'type',
        'title',
        'message',
        'is_send',
        'unread_status',
        'is_seen',
        'time',

    ];

    public function getnotificationTimeAttribute()
    {
        return  (new Carbon($this->created_at))->diffForHumans();

    }
    public static function sendNotifocation(UserNotification $notification)
    {

        $user_id = $notification->to_user_id;
        $devices = Device::where('user_id',$user_id)->get();
        $title = $notification->title;
        $total = UserNotification::where('to_user_id',$user_id)->where('is_seen',0)->count();
        foreach ($devices as $key => $singleDevice) {
            if($singleDevice->type == 'ios'){

                $body['aps']['alert'] =  array('body'=>$notification->message,"title"=>$title);
                $body['aps']['sound'] =  "notification.mp3";
                $body['aps']['badge'] =  $total;
                $body['obj'] =  $notification;
                self::sendIOSNotification($body,$singleDevice,$notification);
            }else{

                $res = array();
                $res['notification']['title'] = $title;
                $res['notification']['body'] = $notification->message;
                $res['notification']['obj'] = $notification;
                $res['notification']['total_unread'] = $total;
                self::sendAndroidNotification($res,$singleDevice,$notification);
            }
        }

    }


    public static function sendIOSNotification($body,$device,UserNotification $notification)
    {

        $device_token = $device->token;

        $certificate = public_path().'/ios_certificates/neighborhood_dev.pem';
        $passphrase = '1234';
        $apple_url = 'ssl://gateway.sandbox.push.apple.com:2195';
        if($device->app_mode == 'live'){
            $certificate = public_path().'/ios_certificates/neighborhood_live.pem';
            $passphrase = '1234';
            $apple_url = 'ssl://gateway.push.apple.com:2195';
        }
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client($apple_url, $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if ($fp){
            $payload = json_encode($body);
            $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));
            if ($result){

                $notification->is_send = 1;
                $notification->save();
                if ($notification->type == 'Message') {
                    $notification->delete();
                }

            }else{

            }
            fclose($fp);
        }

    }


    public static function sendAndroidNotification($body,$device,UserNotification $notification)
    {
        $apiKey = "AAAAF3AeYSY:APA91bG2hHFranrBu39gEQ0l25r8TXrwHBCpCCMUdSVDvwPKdBKNMdu8Cy4kTbJ53NdSemI6hhqkLxay6we-vnc3r9UjIflqik0riu7xwrIMeh-liWuamQA1XKCFXe5766k1ZK2WSdMr";
        $device_token = $device->token;
        $data = array(
            'to' => $device_token,
            'data' => $body,
        );
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {

        }
        Log::info(print_r($result,true));
        // Close connection
        curl_close($ch);
        $notification->is_send = 1;
        $notification->save();
        if ($notification->type == 'Message') {
            $notification->delete();
        }


    }
    public function user()
    {
        return $this->belongsTo(User::class,'from_user_id');
    }

}
