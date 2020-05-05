<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */

namespace App\Traits;

use App\Models\Event;
use App\Models\UserNeighborhood;
use App\Models\EventImage;
use App\Models\EventThank;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait EventApiTrait
 * @package App\Traits
 */
trait EventApiTrait
{
    use UserStatusTrait {
        UserStatusTrait::approvedUsers as approvedUsersList;
    }

    /**
     * Get events  events and event detail by event id
     * @param string $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function events($id = '')
    {
        $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();
        if ($userNeighborhood) {
            $neighborhood_id = $userNeighborhood->neighborhood_id;
            $eventThank = EventThank::where('user_id', Auth::id())->get();
            $events = Event::with(['categories', 'event_images', 'participants' => function ($q) {
                $participant = $q->where('user_id', Auth::id());
                return $participant;
            }])->where('neighborhood_id', $neighborhood_id)
                ->whereIn('user_id', $this->approvedUsersList($neighborhood_id))
                ->orderBy('event_date', 'desc')
                ->when($id, function ($query) use ($id) {
                    if ($id) return $query->where('_id', $id);
                });
                if ($id) {
                        $events = $events->get();
                }else {
                        $events = $events->get()->groupBy(function ($val) {
                        return Carbon::parse($val->event_date)->format('F Y');
                    });
                    
                }
                


                $eventsArr = [];
                foreach ($events as $k => $event) {
                    if($id){
                        $event_id = $event->_id;

                        $event->event_images->map(function ($row) use ($eventThank ) {
                                if(!isset($row->event_media_total_thanks)){
                                    $row['event_media_total_thanks'] =0;
                                }
                                if(!isset($row->event_media_total_replies)){
                                    $row['event_media_total_replies'] =0;
                               }
                               
                               $row['is_liked'] =$eventThank->contains('media_id',$row->_id);
                               return $row;
                        });
                        
                    }
                    else{
                       $event->map(function ($ent) use ($eventThank ) {
                            $event_id = $ent->_id;
                            $ent->event_images->map(function ($row) use ($eventThank ) {
                                if(!isset($row->event_media_total_thanks)){
                                    $row['event_media_total_thanks'] =0;
                                }
                                if(!isset($row->event_media_total_replies)){
                                    $row['event_media_total_replies'] =0;
                               }
                               $row['is_liked'] =$eventThank->contains('media_id',$row->_id);
                               
                               return $row;
                            });
                            
                            return $ent;
                       
                        }); 
                       $singleEventArr['events'] = $event;
                       $eventsArr[] = $singleEventArr;
                    }
                    
                    
                }
                if($id){
                    return $event;
                }else{
                   return $eventsArr; 
                }
                
           

            
            

        } else {
            return [];
        }
    }

     /**
     * @param string $postId string $mediaId
     * @return UserPost|UserPost[]|array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed|mixed[]
     */
    public function eventsMedia($eventId = '' , $mediaId)
    {
        try {
            $userNeighborhood = UserNeighborhood::where('user_id', Auth::id())->first();

            if ($userNeighborhood) {
                $neighborhood_id = $userNeighborhood->neighborhood_id;

                $eventThank = EventThank::where('user_id', Auth::id())->get();
                $event = EventImage::findorFail($mediaId);
                $event['is_liked'] = $eventThank->contains('media_id',$mediaId);              
                if(!isset($event->event_media_total_thanks)){
                    $event['event_media_total_thanks'] =0;
                }
                if(!isset($event->event_media_total_replies)){
                   $event['event_media_total_replies'] =0;
                 }

                $custom_array['_id'] = $event['_id'];
                $custom_array['parent_id'] = $event['event_id'];
                $custom_array['type'] = $event['type'];
                $custom_array['order_id'] = $event['order_id'];
                $custom_array['media_total_replies'] = $event['event_media_total_replies'];
                $custom_array['media_total_thanks'] =$event['event_media_total_thanks'] ;
                $custom_array['is_liked'] = $event['is_liked'];
                $custom_array['full_image'] = $event['full_event_image'];
                $custom_array['full_video'] = $event['full_event_video'];
                return $custom_array;               
                
            }else{
                return [];
            }

        } catch (\Exception $e) {
            return [];
        }
    }

}
