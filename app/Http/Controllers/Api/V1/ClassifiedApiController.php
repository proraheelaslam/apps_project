<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\ClassifiedNotification;
use App\Models\Classified;
use App\Models\ClassifiedCategory;
use App\Models\ClassifiedImage;
use App\Models\ClassifiedThank;
use App\Models\User;
use App\Models\UserNeighborhood;
use App\Traits\ApiResponse;
use App\Traits\ClassifiedApiTrait;
use App\Traits\UserStatusTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;


class ClassifiedApiController extends Controller
{
    //
    use ApiResponse, ClassifiedApiTrait;

    /**
     * Get list of all classifieds/Products
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {


        $classified =  $this->listClassifieds();
        $message = Lang::get('api.classified_list_message');
        if (!empty($classified)) {
            return response()->json(['status'=>true,'message'=>$message,'data'=> $classified]);
        }
        return response()->json(['status'=>true,'message'=>$message,'data'=> $classified]);
    }
    /**
     * Get detail of classifieds/product by classified_id/product_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function classifiedDetail($id)
    {
        if (!$classified = Classified::find($id)){
            $message = Lang::get('api.classified_not_found');
            return $this->sucessResponse(false,$message,[]);
        }
        $classified =  $this->listClassifieds($id);
        $message = Lang::get('api.classified_list_message');
        return $this->sucessResponse(true,$message,$classified);

    }

    /**
     * Get all classifieds/products by category_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryClassifieds($id)
    {
        if (!$classified = ClassifiedCategory::find($id)) {
            $message = Lang::get('api.classified_category_not_found');
            return $this->errorResponse(false,$message,[]);
        }
        $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();
        $classifiedThank = ClassifiedThank::where('user_id', Auth::id())->get();
        if ($userNeighborhood) {
            $classifieds = Classified::with('classified_images', 'categories')
                ->where('neighborhood_id', $userNeighborhood->neighborhood_id)
                ->whereIn('user_id',$this->approvedUsersList($userNeighborhood->neighborhood_id))
                ->where('classicat_id', $id)
                ->orderBy('created_at','desc')
                ->paginate(10);

            $classifieds->map(function ($classified) use ($classifiedThank) {
                $classified_images = $classified->classified_images;
                $classified_id = $classified->_id;
                $classified_images->map(function ($image) use ($classifiedThank,$classified_id ) {
                    if(!isset($image->classified_media_total_thanks)){
                        $image['classified_media_total_thanks'] =0;
                    }
                    if(!isset($image->classified_media_total_replies)){
                        $image['classified_media_total_replies'] =0;
                    }
                    $image['classified_id'] = $classified_id;
                    $image['is_liked'] =$classifiedThank->contains('media_id',$image->_id);
                    return $image;

                });

               
                return $classified;

             });
        }else {
            $classifieds = [];
        }


        $message = Lang::get('api.classified_list_message');
        return $this->sucessResponse(true,$message,$classifieds);
    }
    /**
     * Get all classified/products categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function classifiedCategoriesList()
    {
        $classifiedCategories = ClassifiedCategory::all();
        $message = Lang::get('api.classified_category_message');
        return $this->sucessResponse(true,$message,$classifiedCategories);
    }
    /**
     * Create classified/Product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|min:0|numeric',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:classified_categories,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        if (!$this->checkUserStatus(Auth::id())) {
            $message = Lang::get('api.status_not_approved_message');
            return $this->errorResponse(false, $message, []);
        }
        $classified = new Classified();
        $this->saveClassified($request, $classified,'create');
        $message = Lang::get('api.classified_create_message');
        return $this->sucessResponse(true, $message, $classified);
    }

    /**
     * Save or update  Classified/Product by $request and $classified object
     * @param $request
     * @param $classified
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveClassified($request, $classified,$type)
    {
        try{
            $classified->user_id = Auth::id();
            $classified->neighborhood_id = $request->neighborhood;
            $classified->classified_title = $request->title;
            $classified->classified_description = $request->description;
            $classified->classified_price = (float) $request->price;
            $classified->cstatus_id = $this->classifiedStatus('Pending');
            $classified->classicat_id = $request->category;
            $classified->save();
            //

            if ($type == 'create') {
                dispatch(new ClassifiedNotification($classified, 'classified', Config::get('constant.new_classified')))->delay(now()->addSecond(1));
            }else if ($type == 'update') {

                dispatch(new ClassifiedNotification($classified, 'classified', Config::get('constant.classified_updated')))->delay(now()->addSecond(1));
            }



            return $classified;
        }catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Update classified/product by classified_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:classified_categories,_id',
            'classified_id' => 'required|exists:classifieds,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $classified = Classified::find($request->classified_id);

        if (isset($request->images)) {
            $validator = Validator::make($request->all(), [
                'images' => 'required|json'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $images = json_decode($request->images);
            foreach ($images as $img) {
                if (!empty($img->image_id)) {
                    $classified_image = ClassifiedImage::find($img->image_id);
                    if($classified_image){
                        $classified_image->order_id = $img->order_id;
                        $classified_image->save();
                    }                     
                } 
            }
        }


        $this->saveClassified($request, $classified,'update');
        $message = Lang::get('api.classified_create_message');
        return $this->sucessResponse(true, $message, $classified);
    }

    /**
     * Save Classified/Product Image by classified_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'classified_id' => 'required|exists:classifieds,_id',
                'file' => 'required',
                'order_id' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $file = $request->file('file');
            return $this->uploadFile($request, $file, 'classified',$request->classified_id);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Delete Classified/Product image by image_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'image_id'=> 'required|exists:classified_images,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false,$validator,$data = []);
        $classifiedImage = ClassifiedImage::find($request->image_id);
        $imagePath['image'] = public_path('upload/classifieds/' . $classifiedImage->cimg_image_file);
        return $this->deleteFile($classifiedImage, $imagePath, 'classified');
    }

    /**
     * Delete Classified/Product by classified_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classified_id' => 'required|exists:classifieds,_id',
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        $classified = Classified::find($request->classified_id);
        $classified->delete();
        $message = Lang::get('api.delete_classified');
        return $this->sucessResponse(true,$message,[]);
    }
}
