<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BusinessImage;
use App\Models\BusinessStatus;
use App\Models\ClassifiedImage;
use App\Models\ClassifiedStatus;
use App\Models\EventImage;
use App\Models\EventStatus;
use App\Models\Gender;
use App\Models\Neighborhood;
use App\Models\NeighborhoodStatus;
use App\Models\PostImage;
use App\Models\PostStatus;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserStatus;
use App\Traits\ApiResponse;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;


    /**
     * Get Gender Name by gender_key
     * @param $gender
     * @return mixed
     */
    public function getGenderName($gender)
    {
        $gender =  Gender::select('_id')->where('gender_key', strtolower($gender))->first();
        return $gender->_id;
    }
    /**
     * Get User status by status name
     * @param $status
     * @return mixed
     */
    public function getUserStatus($status)
    {
        $status =  UserStatus::select('_id')->where('ustatus_name',$status)->first();
        return $status->_id;
    }



    public function checkUserStatus($userId)
    {
        $userStatus = User::find($userId)->status->ustatus_name;
        if ($userStatus == 'approved'){
            return true;
        }else {
            return false;
        }
    }

    /**
     * Get User address Pin Code
     * @param $userId
     * @param $code
     * @return mixed
     */
    public function getAddressPinCode($userId,$code)
    {
        return User::where(['_id'=>$userId,'user_address_verify_code'=> (int)$code])->first();
    }
    /**
     * Get Neighbourhood Status id
     * @param $status
     * @return mixed
     */
    public function neighborhoodStatusId($status)
    {
        $status = NeighborhoodStatus::where('nstatus_name',$status)->first();
        return $status->_id;
    }
    /**
     * Get neighbourhood Status name
     * @param $status
     * @return mixed
     */
    public function neighborhoodStatusName($status)
    {
        $status = NeighborhoodStatus::where('nstatus_name',$status)->first();
        return $status->nstatus_name;
    }
    /**
     * Get total neighbourhood users by neighbourhood_id
     * @param $neighborhoodId
     * @return int
     */
    public function neighborhoodTotalUsers($neighborhoodId)
    {
        $neighborhooUser = Neighborhood::where('_id',$neighborhoodId)->first();
        $neighborhooUser->neighborhood_total_users = $neighborhooUser->neighborhood_total_users+1;
        $neighborhooUser->save();
        return $neighborhooUser->neighborhood_total_users;
    }

    /**
     * Mail function that use for sending the Email
     * @param array $view
     * @param $to
     * @param array $data
     * @param $subject
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Get post
     * @param $status
     * @return mixed
     */
    public function postStatus($status)
    {
        $status = PostStatus::where('pstatus_name',$status)->first();
        return $status->_id;
    }
    /**
     * Get Event status id
     * @param $status
     * @return mixed
     */
    public function eventStatus($status)
    {
        $status = EventStatus::where('estatus_name',$status)->first();
        return $status->_id;
    }
    /**
     * Get classified status name
     * @param $status
     * @return mixed
     */
    public function classifiedStatus($status)
    {
        $status = ClassifiedStatus::where('cstatus_name',$status)->first();
        return $status->_id;
    }

    /**
     * Get Business status id
     * @param $status
     * @return mixed
     */
    public function businessStatus($status)
    {
        $status  = BusinessStatus::where('bstatus_name',$status)->first();
        return $status->_id;
    }
    /**
     * Upload the post,event admin_profile,classifieds and business images
     * @param $request
     * @param $file
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile($request,$file,$type,$id = '')
    {
        $resultArr = [];
        $input['file'] = time() . rand(2, 100) . '_' . $file->getClientOriginalName();
        if ($type == 'event') {
            if ($request->type == 'image') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

                $destinationPath = public_path('upload/events');
                $file->move($destinationPath, $input['file']);
            }
            if ($request->type == 'video') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $destinationPath = public_path('upload/events');
                $status = $file->move($destinationPath, $input['file']);
                if ($status) {
                    $ffmpeg = FFMpeg::create();
                    $videName = $input['file'];
                    $videoPath =  public_path('upload/events/').$videName;
                    $video = $ffmpeg->open($videoPath);
                    $frame = $video->frame(TimeCode::fromSeconds(0.1));
                    $thumbnailName = time() . rand(2, 100) . '_'.'video.jpg';
                    $thumbnailPath = public_path('upload/events/').$thumbnailName;
                    $frame->save($thumbnailPath);
                }
            }
            // upload file
            $eventImage = new EventImage();
            $eventImage->event_id = $request->event_id;
            $eventImage->eimg_image_file = ($request->type == 'video' ? $thumbnailName: $input['file']);
            $eventImage->video_file =  ($request->type == 'video' ? $videName: '');
            $eventImage->type = $request->type;
            $eventImage->order_id = $request->order_id;
            $eventImage->save();
            $resultArr =  $this->events($id);
            // ...... end event section
        } 
        elseif ($type == 'post') {

            if ($request->type == 'image') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

                $destinationPath = public_path('upload/posts');
                $file->move($destinationPath, $input['file']);
            }
            if ($request->type == 'video') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $destinationPath = public_path('upload/posts');
                $status = $file->move($destinationPath, $input['file']);
                if ($status) {
                    $ffmpeg = FFMpeg::create();
                    $videName = $input['file'];
                    $videoPath =  public_path('upload/posts/').$videName;
                    $video = $ffmpeg->open($videoPath);
                    $frame = $video->frame(TimeCode::fromSeconds(0.1));
                    $thumbnailName = time() . rand(2, 100) . '_'.'video.jpg';
                    $thumbnailPath = public_path('upload/posts/').$thumbnailName;
                    $frame->save($thumbnailPath);
                }
            }
            $userPost = new PostImage();
            $userPost->upost_id = $request->post_id;
            $userPost->pimg_image_file = ($request->type == 'video' ? $thumbnailName: $input['file']);
            $userPost->video_file =  ($request->type == 'video' ? $videName: '');
            $userPost->type = $request->type;
            $userPost->order_id = $request->order_id;
            $userPost->upost_media_total_thanks = 0;
            $userPost->upost_media_total_replies = 0;
            $userPost->save();
            $resultArr =  $this->posts($id);
            // ...... end post section
        } 
        elseif ($type == 'classified') {
            //dd($id);
            $validator = Validator::make($request->all(), [
                'file' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $destinationPath = public_path('upload/classifieds');
            $status = $file->move($destinationPath, $input['file']);
            if ($status) {
                $classified = new ClassifiedImage();
                $classified->classified_id = $request->classified_id;
                $classified->cimg_image_file =  $input['file'];
                $classified->type =  'image';
                $classified->order_id = $request->order_id;
                $classified->save();
                $resultArr =  $this->listClassifieds($id);
            }
            //  ...... end classified section
        } 
        elseif ($type == 'business') {

            if ($request->type == 'image') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

                $destinationPath = public_path('upload/businesses');
                $file->move($destinationPath, $input['file']);
            }
            if ($request->type == 'video') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required',
                ]);
                if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
                $destinationPath = public_path('upload/businesses');
                $status = $file->move($destinationPath, $input['file']);
                if ($status) {
                    $ffmpeg = FFMpeg::create();
                    $videName = $input['file'];
                    $videoPath =  public_path('upload/businesses/').$videName;
                    $video = $ffmpeg->open($videoPath);
                    $frame = $video->frame(TimeCode::fromSeconds(0.1));
                    $thumbnailName = time() . rand(2, 100) . '_'.'video.jpg';
                    $thumbnailPath = public_path('upload/businesses/').$thumbnailName;
                    $frame->save($thumbnailPath);
                }
            }
            // upload file
            $business = new BusinessImage();
            $business->business_id = $request->business_id;
            $business->bimg_name = ($request->type == 'video' ? $thumbnailName: $input['file']);
            $business->video_file =  ($request->type == 'video' ? $videName: '');
            $business->type = $request->type;
            $business->order_id = $request->order_id;
            $business->save();
            $resultArr =  $this->businesses($id);

            /*$validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $destinationPath = public_path('upload/businesses');
            $status = $file->move($destinationPath, $input['file']);
            if ($status) {
                $business = new BusinessImage();
                $business->business_id = $request->business_id;
                $business->bimg_name =  $input['file'];
                $business->type =  'image';
                $business->save();
                $resultArr =  $this->businesses($id);
            }*/
        }
        elseif ($type == 'admin_profile') {

            $validator = Validator::make($request->all(), [
                'profile_image' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $destinationPath = public_path('upload/profile');
            $status = $file->move($destinationPath, $input['file']);
            if ($status) {
                $admin = Admin::find($id);
                $admin->profile_image = $input['file'];
                $admin->save();
            }
        }  //  ...... end classified section

        $message = Lang::get('api.file_upload_image_message');
        return $this->sucessResponse(true, $message, $resultArr);
    }
    /**
     * Delete the file by condition base, post,classified,events,business
     * @param $imgObject
     * @param $imagePath
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile($fileObject,$imagePath,$type)
    {
        try {
            $resultArr = [];
            if (File::exists($imagePath['image']) || File::exists($imagePath['video'])) {
                if ($fileObject->type == 'video') {
                    File::delete($imagePath['video']);
                    File::delete($imagePath['image']);
                }else if ($fileObject->type == 'image'){
                    File::delete($imagePath['image']);
                }
                $fileObject->delete();
                if ($type == 'event') {
                    $resultArr = $this->events($fileObject->event_id);
                } elseif ($type == 'post') {
                    $resultArr = $this->posts($fileObject->upost_id);
                } elseif ($type == 'classified'){
                     $resultArr = $this->listClassifieds($fileObject->classified_id);
                } elseif ($type == 'business'){
                    $resultArr = $this->businesses($fileObject->business_id);
                }
                $message = Lang::get('api.delete_image');
                return $this->sucessResponse(true, $message, $resultArr);
            } else {
                $message = Lang::get('api.image_not_exist');
                return $this->sucessResponse(true, $message, []);
            }
        }catch (\Exception $e) {
           return $this->exceptionResponse();
        }

    }
}
