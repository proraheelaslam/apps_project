<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\BusinessNotification;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessImage;
use App\Models\BusinessRecommendations;
use App\Models\BusinessLike;
use App\Models\BusinessReport;
use App\Models\BusinessReportReason;
use App\Traits\BusinessApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;


class BusinessApiController extends Controller
{
    use BusinessApiTrait;

    /**
     * Get Business list
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $businesses =  $this->businesses();
        $message = Lang::get('api.business_list_message');
        return response()->json(['status'=>true,'message'=>$message,'data'=> $businesses]);
    }

    /**
     * Create Business
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'latitude'=> 'required',
            'longitude'=> 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:businesses,business_email',
            'detail' => 'required',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:business_categories,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        if (!$this->checkUserStatus(Auth::id())) {
            $message = Lang::get('api.status_not_approved_message');
            return $this->errorResponse(false, $message, []);
        }
        $busines =  new Business();
        $businessResult = $this->saveBusiness($request,$busines,'create');
        $message = Lang::get('api.business_create_message');
        return $this->sucessResponse(true,$message,$businessResult);
    }

    /**
     * Update the Business
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'latitude'=> 'required',
            'longitude'=> 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'detail' => 'required',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:business_categories,_id',
            'business_id'=>'required|exists:businesses,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $busines = Business::find($request->business_id);


        if (isset($request->images)) {
            $validator = Validator::make($request->all(), [
                'images' => 'required|json'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $images = json_decode($request->images);
            foreach ($images as $img) {
                if (!empty($img->image_id)) {
                    $business_image = BusinessImage::find($img->image_id);
                    if($business_image){
                        $business_image->order_id = $img->order_id;
                        $business_image->save();
                    }                     
                } 
            }
        }
        $this->saveBusiness($request,$busines,'update');
        $busineses = $this->businesses($request->business_id);
        $message = Lang::get('api.business_create_message');
        return $this->sucessResponse(true,$message,$busineses);

    }

    /**
     * Save Business
     * @param $request
     * @param $busines
     * @return mixed
     */
    private function saveBusiness($request,$busines,$type)
    {
        $busines->user_id = Auth::id();
        $busines->neighborhood_id = $request->neighborhood;
        $busines->business_name = $request->name;
        $busines->business_address = $request->address;
        $busines->business_phone = $request->phone;
        $busines->business_email =  $request->email;
        $busines->business_website =  (isset($request->website) && $request->has('website') ? $request->website : '');
        $busines->business_details = $request->detail;
        $busines->business_is_approved = ($busines->business_is_approved == 0 ? 1  : $busines->business_is_approved);
        $busines->latitude = $request->latitude;
        $busines->longitude = $request->longitude;
        $busines->business_total_likes = ($busines->business_total_likes == null ? 0 : $busines->business_total_likes);
        $busines->business_total_recommended = ($busines->business_total_recommended == null ? 0 : $busines->business_total_recommended);
        $busines->category_id = $request->category;
        $busines->bstatus_id = $this->businessStatus('Pending');
        $busines->bussiness_isapproved_by = 0;
        $busines->save();

        if ($type == 'create') {
            dispatch(new BusinessNotification($busines, 'business', Config::get('constant.new_business'),'create'))->delay(now()->addSecond(1));
        }else if ($type == 'update') {

            dispatch(new BusinessNotification($busines, 'business', Config::get('constant.business_update'),'update'))->delay(now()->addSecond(1));
        }

        return $busines;
    }

    /**
     * Get Business Detail by business_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessDetail($id)
    {
        if (!$business = Business::find($id)) {
            $message = Lang::get('api.business_id_not_found_message');
            return $this->errorResponse(false, $message, []);
        }
        $business = $this->businesses($id);
        $message = Lang::get('api.business_list_message');
        return $this->sucessResponse(true,$message,$business);

    }

    /**
     * Recommend the Business by business_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommendBusiness(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $businessRecommend = BusinessRecommendations::where('user_id',Auth::id(),'business_id',$request->business_id)->first();
        if ($businessRecommend) {
            BusinessRecommendations::where('user_id',Auth::id(),'business_id',$request->business_id)->delete();

        }else{
            $businessRecomendation = new BusinessRecommendations();
            $businessRecomendation->user_id = Auth::id();
            $businessRecomendation->business_id = $request->business_id;
            $businessRecomendation->save();
        }
        $business = Business::find($request->business_id);
        $business->business_total_recommended = $business->business_recommended->count();
        $business->save();
        $businesses = $this->businesses($request->business_id);
        if ($businessRecommend) {
            dispatch(new BusinessNotification($business, 'business', Config::get('constant.business_recommended'), 'recommended'))->delay(now()->addSecond(1));
        }
        $message = Lang::get('api.business_recommend_message');
        return $this->sucessResponse(true,$message,$businesses);
    }

    /**
     * Save Business Image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'business_id' => 'required|exists:businesses,_id',
                'type' => 'in:video,image',
                'file' => 'required',
                'order_id' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $file = $request->file('file');
            return $this->uploadFile($request, $file, 'business',$request->business_id);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Business Categories list
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessCategoriesList()
    {
        $businessCategory = BusinessCategory::all();
        $message = Lang::get('api.business_category_list_message');
        return $this->sucessResponse(true, $message, $businessCategory);
    }
    /**
     * Delete the Business Image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'image_id'=> 'required|exists:business_images,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false,$validator,$data = []);
        $businessImage= BusinessImage::find($request->image_id);
        $imagePath['image'] = public_path('upload/businesses/' . $businessImage->bimg_name);
        return $this->deleteFile($businessImage, $imagePath, 'business');
    }

    /**
     * Give thanks on business by business_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessLikes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id'=>'required|exists:businesses,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $businessLikes = BusinessLike::where(['user_id' => Auth::id(), 'business_id' => $request->business_id])->first();
        if ($businessLikes) {
            BusinessLike::where(['user_id' => Auth::id(), 'business_id' => $request->business_id])->delete();
        } else {
            $businessLikes = new BusinessLike();
            $businessLikes->business_id = $request->business_id;
            $businessLikes->user_id = Auth::id();
            $businessLikes->save();
        }
        $business = Business::find($request->business_id);
        $business->business_total_likes = $business->likes->count();
        $business->save();
        $businesses =  $this->businesses($request->business_id);
        $message = Lang::get('api.business_thank_message');
        return $this->sucessResponse(true, $message, $businesses);
    }

    /**
     * Get business recommended users list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessRecommendedUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id'=>'required|exists:businesses,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $users = BusinessRecommendations::with('users')->where('business_id',$request->business_id)->get();
        $message = Lang::get('api.recommended_users_list_message');
        return $this->sucessResponse(true, $message, $users);
    }

    /**
     * save Business report by reason id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'breport_reason_id'=>'required|exists:business_report_reasons,_id',
            'business_id'=>'required|exists:businesses,_id',
            'comment'=> 'required'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

        $breportReason = new BusinessReport();
        $breportReason->brreason_id = $request->breport_reason_id;
        $breportReason->breport_comment = $request->comment;
        $breportReason->reported_by = Auth::id();
        $breportReason->business_id = $request->business_id;
        $breportReason->save();
        $message = Lang::get('api.business_report_save_message');
        return $this->sucessResponse(true, $message, $breportReason);

    }

    /**
     * Get all business Report reasons
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessReportReasons()
    {
        $brReasons  =  BusinessReportReason::all();
        $message = Lang::get('api.breport_reason_list_message');
        return $this->sucessResponse(true, $message,$brReasons);
    }
    /**
     * Delete the Business by business_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id'=>'required|exists:businesses,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $business = Business::find($request->business_id);
        $business->delete();
        $businesses =  $this->businesses($request->business_id);
        $message = Lang::get('api.business_delete_message');
        return $this->sucessResponse(true,$message,$businesses);
    }


    /**
     * Get Business list with filter of catgeory
     * @return \Illuminate\Http\JsonResponse
    */
    public function businessCategoryFilter($category_id)
    {
        /* if (!$business = Business::where('category_id',$category_id)->first()) {
            $message = Lang::get('api.business_id_not_found_message');
            return $this->errorResponse(false, $message, []);
        }*/
        $business = $this->category_businesses($category_id);
        $message = Lang::get('api.business_list_message');
        return $this->sucessResponse(true,$message,$business);
    }
}
