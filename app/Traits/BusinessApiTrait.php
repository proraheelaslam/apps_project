<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */
namespace App\Traits;

use App\Models\Business;
use App\Models\BusinessLike;
use App\Models\BusinessRecommendations;
use App\Models\BusinessImage;
use App\Models\BusinessThank;
use App\Models\BusinessReply;
use App\Models\UserNeighborhood;
use Illuminate\Support\Facades\Auth;

/**
 * Trait BusinessApiTrait
 * @package App\Traits
 */
trait BusinessApiTrait
{
    use UserStatusTrait{
        UserStatusTrait::approvedUsers as approvedUsersList;
    }
    /**
     * Get All Business or Business Detail by business_id
     * @param string $id
     * @return Business|Business[]|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function businesses($id = '')
    {
        try {
            $bsnsRecom = BusinessRecommendations::where('user_id', Auth::id())->get();
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();
            $businessLike = BusinessLike::where('user_id', Auth::id())->get();
            $businessThank = BusinessThank::where('user_id', Auth::id())->get();
            if ($userNeighborhood) {
                $businesses = Business::with('users','categories','business_images')
                    ->where('neighborhood_id', $userNeighborhood->neighborhood_id)
                    ->whereIn('user_id',$this->approvedUsersList($userNeighborhood->neighborhood_id))
                    ->where('business_is_approved', 1)
                    ->when($id,function ($query) use($id) {
                        if ($id) return $query->where('_id',$id);
                    })
                    ->orderBy('business_name','asc');
                if ($id) {
                    $businesses = $businesses->get();
                }else {
                    $businesses = $businesses->paginate(10);
                }
                $businesses->map(function ($business) use ($bsnsRecom, $businessLike, $businessThank) {
                    $business['is_recommended'] = $bsnsRecom->contains('business_id', $business->_id);
                    $business['is_liked'] = $businessLike->contains('business_id', $business->_id);
                    $business['total_recommended'] = count($bsnsRecom);


                    $business_images = $business->business_images;
                    $business_id = $business->_id;
                    $business_images->map(function ($image) use ($businessThank,$business_id ) {
                        if(!isset($image->business_media_total_thanks)){
                            $image['business_media_total_thanks'] =0;
                        }
                        if(!isset($image->business_media_total_replies)){
                            $image['business_media_total_replies'] =0;
                        }
                        $image['business_id'] = $business_id;
                        $image['is_liked'] =$businessThank->contains('media_id',$image->_id);
                        return $image;
                    });
                    return $business;
                });
                if ($id) {
                    return $businesses[0];
                }else {
                    return $businesses;
                }

            } else {
                return [];
            }
        }catch (\Exception $e){
            return [];
        }
    }

     /**
     * Get All Business of a specific category
     * @param string $category_id
     * @return Business|Business[]|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function category_businesses($category_id = '')
    {
        try {
            $bsnsRecom = BusinessRecommendations::where('user_id', Auth::id())->get();
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();
            $businessLike = BusinessLike::where('user_id', Auth::id())->get();
            $businessThank = BusinessThank::where('user_id', Auth::id())->get();
            if ($userNeighborhood) {
                $businesses = Business::with('users','categories','business_images')
                    ->where('neighborhood_id', $userNeighborhood->neighborhood_id)
                    ->whereIn('user_id',$this->approvedUsersList($userNeighborhood->neighborhood_id))
                    ->where('business_is_approved', 1)
                    ->where('category_id',$category_id)
                    ->orderBy('business_name','asc');
                
                $businesses = $businesses->paginate(10);
                
                $businesses->map(function ($business) use ($bsnsRecom, $businessLike,$businessThank) {
                    $business['is_recommended'] = $bsnsRecom->contains('business_id', $business->_id);
                    $business['is_liked'] = $businessLike->contains('business_id', $business->_id);
                    $business['total_recommended'] = count($bsnsRecom);



                    $business_images = $business->business_images;
                    $business_id = $business->_id;
                    $business_images->map(function ($image) use ($businessThank,$business_id ) {
                        if(!isset($image->business_media_total_thanks)){
                            $image['business_media_total_thanks'] =0;
                        }
                        if(!isset($image->business_media_total_replies)){
                            $image['business_media_total_replies'] =0;
                        }
                        $image['business_id'] = $business_id;
                        $image['is_liked'] =$businessThank->contains('media_id',$image->_id);
                        return $image;
                    });
                    return $business;
                });
                
                return $businesses;
               

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
    public function businessMedia($businessId = '' , $mediaId)
    {
        try {
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();

            if ($userNeighborhood) {
                $neighborhood_id = $userNeighborhood->neighborhood_id;

                $businessThank = BusinessThank::where('user_id', Auth::id())->get();
                $business = BusinessImage::findorFail($mediaId);
                $business['is_liked'] = $businessThank->contains('media_id',$mediaId);              
                if(!isset($business->business_media_total_thanks)){
                    $business['business_media_total_thanks'] =0;
                }
                if(!isset($business->business_media_total_replies)){
                   $business['business_media_total_replies'] =0;
                 }

                $custom_array['_id'] = $business['_id'];
                $custom_array['parent_id'] = $business['business_id'];
                $custom_array['type'] = $business['type'];
                $custom_array['order_id'] = $business['order_id'];
                $custom_array['media_total_replies'] = $business['business_media_total_replies'];
                $custom_array['media_total_thanks'] =$business['business_media_total_thanks'] ;
                $custom_array['is_liked'] = $business['is_liked'];
                $custom_array['full_image'] = $business['full_business_image'];
                $custom_array['full_video'] =$business['full_business_video'];
                return $custom_array;


                
            }else{
                return [];
            }

        } catch (\Exception $e) {
            return [];
        }
    }
}