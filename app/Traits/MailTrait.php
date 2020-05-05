<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */
namespace App\Traits;


use Illuminate\Support\Facades\Mail;

/**
 * Trait BusinessApiTrait
 * @package App\Traits
 */
trait MailTrait
{
    public function sendMail($view=[],$to,$data=[],$subject)
    {
        try{
            Mail::send($view, $data, function($message) use($to,$subject) {
                $message->to($to)->subject($subject);
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_TITLE'));
            });

        }catch (\Exception $e) {
            return response()->json(['message'=>'Email is not sent']);
        }
    }
}