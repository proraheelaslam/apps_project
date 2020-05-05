<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $userData['id'] =  $this->user->_id;
        $userData['user_email'] =  $this->user->user_email;
        $userData['user_fname'] = $userData;
        $userDataEncode = base64_encode(json_encode($userData));
        $this->user->remember_token = $userDataEncode;
        $this->user->save();
        // send reset password email
        $emailTemplate = EmailTemplate::where('key', 'reset_password')->first();
        $userLink = url('user/password/reset/' . $userDataEncode);
        Log::error($userLink);
        $view = "user.emails.reset_password_template";
        $emailTemplate->content = str_replace(['{email_link}'], [$userLink], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view,  $this->user->user_email, ['mailData' => $mailData], $emailTemplate->subject);
    }

    private function sendMail($view=[],$to,$data=[],$subject)
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
