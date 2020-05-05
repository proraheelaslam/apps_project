<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */
namespace App\Traits;

use App\Models\Ad;
use App\Models\PostAnswer;
use App\Models\PostQuestion;
use App\Models\PostThank;
use App\Models\UserNeighborhood;
use App\Models\UserPost;
use App\Models\PostImage;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait PostApiTrait
 * @package App\Traits
 */
trait PostApiTrait
{
    use UserStatusTrait{
        UserStatusTrait::approvedUsers as approvedUsersList;
    }

    /**
     * @param string $postId
     * @return UserPost|UserPost[]|array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function posts($postId = '')
    {
        try {
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();

            if ($userNeighborhood) {
                $neighborhood_id = $userNeighborhood->neighborhood_id;

                $postThank = PostThank::where('user_id', Auth::id())->get();
                $postAnswer = PostAnswer::where('user_id', Auth::id())->get();
                $neighboodsPosts = UserPost::with(['post_images', 'users', 'post_questions'])
                    ->where('neighborhood_id', $neighborhood_id)
                    ->whereIn('user_id',$this->approvedUsersList($userNeighborhood->neighborhood_id))
                    ->when($postId,function ($query) use($postId) {
                        if ($postId) return $query->where('_id',$postId);
                    })
                    ->orderBy('created_at', 'desc');

                if ($postId) {
                    $neighboodsPosts = $neighboodsPosts->get();
                }else {
                    $neighboodsPosts = $neighboodsPosts->get();
                }
                $neighboodsPosts->map(function ($post) use ($postThank, $postAnswer ) {
                        $post_images = $post->post_images;
                        $post_id = $post->_id;
                        $post_images->map(function ($image) use ($postThank, $postAnswer,$post_id ) {
                            if(!isset($image->upost_media_total_thanks)){
                                $image['upost_media_total_thanks'] =0;
                            }
                            if(!isset($image->upost_media_total_replies)){
                                $image['upost_media_total_replies'] =0;
                            }
                            $image['post_id'] = $post_id;
                            $image['is_liked'] = $postThank->contains('media_id',$image->_id);
                            return $image;

                        });

                        if ($post->post_type == 'poll') {
                            $post['is_poll_ended'] = ($post->upost_poll_end_date < Carbon::today()->toDateString() ? true : false);
                        }
                        $post['post_views'] = count($post->views);
                        $post['is_liked'] = $postThank->contains('upost_id', $post->_id);
                        $totalQuestion = PostQuestion::where('upost_id', $post->_id)->get();
                        $post->post_questions->map(function ($question) use ($postAnswer, $totalQuestion, $post) {
                            $qAnswers = PostAnswer::where('pquestion_id', $question->_id)->get();
                            $totalAns = PostAnswer::where('upost_id', $post->_id)->get();
                            $singleTotalAnswer = count($qAnswers);
                            $percentage = 0;
                            if ($singleTotalAnswer != 0 || count($totalAns) != 0) {
                                $percentage = ($singleTotalAnswer / count($totalAns)) * 100;
                            }
                            $question['is_answer'] = $postAnswer->contains('pquestion_id', $question->_id);
                            $question['percentage'] = round($percentage);
                            return $question;
                        });
                        return $post;



                });
                if ($postId) {
                    return @$neighboodsPosts[0];
                }else {

                    $i = 1;
                    $arr = [];
                    $record_limit = 3;
                    foreach ($neighboodsPosts as $key => $value) {
                        $ads = Ad::first();
                        if ($i % $record_limit == 0 ) {
                            $ads['post_type'] = 'google_ads';
                            $arr[] = $ads;
                            $arr[] = $value;
                            $record_limit = 3;
                        }else {
                            $arr[] = $value;
                        }
                        $i++;
                    }
                    $postsArr =  $this->postsPagination($arr);
                   return $postsArr;
                }
            }else{
                return [];
            }

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param string $postId string $mediaId
     * @return UserPost|UserPost[]|array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function postsMedia($postId = '' , $mediaId)
    {
        try {
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();

            if ($userNeighborhood) {
                $neighborhood_id = $userNeighborhood->neighborhood_id;

                $postThank = PostThank::where('user_id', Auth::id())->get();
                $postAnswer = PostAnswer::where('user_id', Auth::id())->get();
                $neighboodsPosts = PostImage::findorFail($mediaId);
                $neighboodsPosts['is_liked'] = $postThank->contains('media_id',$mediaId);

                $custom_array['_id'] = $neighboodsPosts['_id'];
                $custom_array['parent_id'] = $neighboodsPosts['upost_id'];
                $custom_array['type'] = $neighboodsPosts['type'];
                $custom_array['order_id'] = $neighboodsPosts['order_id'];
                $custom_array['media_total_replies'] = $neighboodsPosts['upost_media_total_replies'];
                $custom_array['media_total_thanks'] =$neighboodsPosts['upost_media_total_thanks'] ;
                $custom_array['is_liked'] = $neighboodsPosts['is_liked'];
                $custom_array['full_image'] = $neighboodsPosts['full_post_image'];
                $custom_array['full_video'] = $neighboodsPosts['full_post_video'];
                return $custom_array;

               
                
            }else{
                return [];
            }

        } catch (\Exception $e) {
            return [];
        }
    }

    /**Make paganation from array posts
     * @param $posts
     * @return LengthAwarePaginator
     */
    public function postsPagination($posts)
    {


        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $currentPage = $page ?: (Paginator::resolveCurrentPage() ? : 1);

        $itemCollection = array_slice($posts,($currentPage-1)*$perPage,$perPage);
        $paginator = new LengthAwarePaginator($itemCollection,count($posts),$perPage,$currentPage);

        return $paginator;
    }
}

?>