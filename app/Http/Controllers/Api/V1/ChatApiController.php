<?php

namespace App\Http\Controllers\Api\V1;


use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Models\Chatthreads;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
class ChatApiController extends Controller
{
    use NotificationTrait;
    /** Upload file on server and return only file url
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'type' =>'required',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $file = $request->file('file');
        $input['file'] = time() . rand(2, 100) . '_' . $file->getClientOriginalName();
        $thumbnailPath = '';
        $thumbnailName = '';
        if($request->type == 'video'){
               $destinationPath = public_path('upload/chat');
                $status = $file->move($destinationPath, $input['file']);
                if ($status) {
                    $ffmpeg = FFMpeg::create();
                    $videName = $input['file'];
                    $videoPath =  public_path('upload/chat/').$videName;
                    $video = $ffmpeg->open($videoPath);
                    $frame = $video->frame(TimeCode::fromSeconds(0.1));
                    $thumbnailName = time() . rand(2, 100) . '_'.'video.jpg';
                    $thumbnailPath = public_path('upload/chat/').$thumbnailName;
                    $frame->save($thumbnailPath);
                }
        }else{
           $destinationPath = public_path('upload/chat');
            $file->move($destinationPath, $input['file']);  
        }
    
        if (File::exists(public_path('upload/chat/'.$input['file']))){
            $filePath =  asset('upload/chat/'.$input['file']);
        }else{
            $path = explode('/',$input['file']);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'chat'){
                    $place_holder = 'no_image.png';
                }
            }

            $filePath =  asset('upload/'.$place_holder);
        }
        if($thumbnailName){
            if(File::exists(public_path('upload/chat/'.$thumbnailName))){
                $thumbnailPath =  asset('upload/chat/'.$thumbnailName);
            }
        }
        $message = Lang::get('api.file_upload_image_message');
        return $this->sucessResponse(true, $message, ['file_name'=>$input['file'],'file_path'=>$filePath,'thumb_name'=>$thumbnailName,'thumbnailPath'=>$thumbnailPath]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendChatNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $chatthread =  Chatthreads::find($request->message_id);
        $this->chatNotification($chatthread,'Message');
        $message = Lang::get('api.chat_notification_message');
        return $this->sucessResponse(true, $message, []);

    }
}
