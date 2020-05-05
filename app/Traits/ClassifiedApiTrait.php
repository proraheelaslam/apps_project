<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */
namespace App\Traits;

use App\Models\Classified;
use App\Models\ClassifiedImage;
use App\Models\ClassifiedThank;
use App\Models\UserNeighborhood;
use Illuminate\Support\Facades\Auth;

/**
 * Trait ClassifiedApiTrait
 * @package App\Traits
 */
trait ClassifiedApiTrait
{
    use UserStatusTrait{
        UserStatusTrait::approvedUsers as approvedUsersList;
    }
    /**
     * Get All Classifieds listing or Detail by classified_id
     * @param string $id
     * @return Classified|Classified[]|array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function listClassifieds($id = '')
    {
        try{
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();
            if ($userNeighborhood) {
                $classifiedThank = ClassifiedThank::where('user_id', Auth::id())->get();
                $classifieds = Classified::with('categories', 'classified_images')
                    ->where('neighborhood_id', $userNeighborhood->neighborhood_id)
                    ->whereIn('user_id',$this->approvedUsersList($userNeighborhood->neighborhood_id))
                    ->when($id, function ($classifeid) use ($id) {
                        return $classifeid->where('_id', $id);
                    });
                if ($id) {
                    $classifieds = $classifieds->get();
                    //return $classifieds[0];
                }else{
                    $classifieds = $classifieds->paginate(10);
                    //return $classifieds;
                }



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
            if ($id) {
                return @$classifieds[0];
            } else {
                return $classifieds;
            }
            } else {
                return [];
            }
        }catch (\Exception $e){
            return [];
        }


    }

    /**
     * @param string $postId string $mediaId
     * @return UserPost|UserPost[]|array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function classifiedMedia($classifiedId = '' , $mediaId)
    {
        try {
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();

            if ($userNeighborhood) {
                $neighborhood_id = $userNeighborhood->neighborhood_id;

                $classifiedThank = ClassifiedThank::where('user_id', Auth::id())->get();
                $classified = ClassifiedImage::findorFail($mediaId);
                $classified['is_liked'] = $classifiedThank->contains('media_id',$mediaId);              
                if(!isset($classified->classified_media_total_thanks)){
                    $classified['classified_media_total_thanks'] =0;
                }
                if(!isset($classified->classified_media_total_replies)){
                   $classified['classified_media_total_replies'] =0;
                 }
                

                $custom_array['_id'] = $classified['_id'];
                $custom_array['parent_id'] = $classified['classified_id'];
                $custom_array['type'] = $classified['type'];
                $custom_array['order_id'] = $classified['order_id'];
                $custom_array['media_total_replies'] = $classified['classified_media_total_replies'];
                $custom_array['media_total_thanks'] =$classified['classified_media_total_thanks'] ;
                $custom_array['is_liked'] = $classified['is_liked'];
                $custom_array['full_image'] = $classified['full_classified_image'];
                $custom_array['full_video'] ='';
                return $custom_array;

                
            }else{
                return [];
            }

        } catch (\Exception $e) {
            return [];
        }
    }
}