<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\EventNotification;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventImage;
use App\Models\EventParticipant;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\EventApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class EventApiController extends Controller
{
    use ApiResponse, EventApiTrait;

    /**
     * Get all events
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $events = $this->events();
        $message = Lang::get('api.event_list_message');
        if (!empty($events)) {
            return response()->json(['status'=>true,'message'=>$message,'data'=> $events]);
        }
        // return array [] in case if event not exist
        return response()->json(['status'=>true,'message'=>$message,'data'=> $events]);
    }

    /**
     * Get Event Detail by event_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventDetail($id)
    {
        if (!$event = Event::find($id)) {
            $message = 'Event id is invalid';
            return $this->sucessResponse(true, $message, []);
        }
        $eventDetail = $this->events($id);
        $message = Lang::get('api.event_list_message');
        return $this->sucessResponse(true, $message, $eventDetail);
    }

    /**
     * Create new Event
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date' => 'required',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:event_categories,_id',
            'is_notify_rsvp' => 'required|in:0,1'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
        if (!$this->checkUserStatus(Auth::id())) {
            $message = Lang::get('api.status_not_approved_message');
            return $this->errorResponse(false, $message, []);
        }
        $event = new Event();
        $this->saveEvent($request, $event,'create');
        $message = Lang::get('api.event_create_message');
        return $this->sucessResponse(true, $message, $event);
    }

    /**
     * Save or update Event by $request and $event object
     * @param $request
     * @param $event
     * @return mixed
     */
    private function saveEvent($request, $event,$type)
    {
        try {
            $event->user_id = Auth::id();
            $event->neighborhood_id = $request->neighborhood;
            $event->event_description = $request->description;
            $event->ecategory_id = $request->category;
            $event->title = $request->title;
            $event->event_locations = $request->location;
            $event->event_date = date('Y-m-d H:i:s', strtotime($request->date));
            $event->event_total_joining = ($event->event_total_joining == null ? 0 : $event->event_total_joining);
            $event->event_total_maybe = ($event->event_total_maybe == null ? 0 : $event->event_total_maybe);
            $event->is_notify_rsvp = $request->is_notify_rsvp;
            $event->estatus_id = $this->eventStatus('Pending');
            $event->save();
            //

            if ($type == 'create') {
                dispatch(new EventNotification($event, 'event', Config::get('constant.new_event'),'create'))->delay(now()->addSecond(1));
            }else if ($type == 'update') {

                dispatch(new EventNotification($event, 'event', Config::get('constant.event_update'),'update'))->delay(now()->addSecond(1));
            }

            return $event;

        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }

    }

    /**
     * Join the event by event_id and participant_typ
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinEvent(Request $request)
    {
        try {
            $rules = [
                'event_id' => 'required|exists:events,_id',
                'participation_type' => 'required|in:yes,no,maybe',
                'comment' => 'required',
            ];
            if ($request->participation_type == 'yes' || $request->participation_type == 'maybe') {
                $rules = [
                    'children' => 'required|numeric|min:0',
                    'adults' => 'required|numeric|min:0',
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $eventParticipant = new EventParticipant();

            $eventParticpnt = EventParticipant::where('event_id', $request->event_id)
                ->where('user_id', Auth::id())
                ->first();
            if ($eventParticpnt) {
                EventParticipant::where('event_id', $request->event_id)->where('user_id', Auth::id())->delete();
            }
            $event = Event::find($request->event_id);
            if ($event) {
                $eventParticipant->user_id = Auth::id();
                $eventParticipant->event_id = $request->event_id;
                $eventParticipant->participation_type = $request->participation_type;
                $eventParticipant->epart_children = ($request->participation_type == 'no' ? 0 : $request->children);
                $eventParticipant->epart_adults = ($request->participation_type == 'no' ? 0 : $request->adults);
                $eventParticipant->epart_comment = $request->comment;
                $eventParticipant->save();
                $this->updateParticipantCount($event->_id);
                $message = Lang::get('api.event_join_message');
                return $this->sucessResponse(true, $message, []);
            }

            $message = Lang::get('api.event_join_message');
            return $this->sucessResponse(true, $message, $event);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Update the Number of participants that join the event
     * @param $eventId
     */
    private function updateParticipantCount($eventId)
    {

        $eparticipants = EventParticipant::where('event_id', $eventId)->where('participation_type', 'yes')->get();
        $totalParticipant = 0;
        foreach ($eparticipants as $epart) {
            $totalParticipant = $totalParticipant + $epart->epart_children + $epart->epart_adults;
        }
        $eparticipantsMabe = EventParticipant::where('event_id', $eventId)->where('participation_type', 'maybe')->get();
        $totalMaybeParticipant = 0;
        foreach ($eparticipantsMabe as $epartMaybe) {
            $totalMaybeParticipant = $totalMaybeParticipant + $epartMaybe->epart_children + $epartMaybe->epart_adults;
        }
        $event = Event::find($eventId);
        $event->event_total_joining = $totalParticipant;
        $event->event_total_maybe = $totalMaybeParticipant;
        $event->save();
    }

    /**
     * Get all joined events by event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinEventList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,_id',
                'type' => 'required|in:yes,maybe',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $joinEvents = EventParticipant::with('users')
                ->where('event_id', $request->event_id)
                ->where('participation_type', $request->type)->paginate(10);
            $message = Lang::get('api.event_join_list_message');
            return $this->sucessResponse(true, $message, $joinEvents);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Invitation send of Event by email and event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,_id',
                'email' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $event = Event::find($request->event_id);
            if ($event) {
                $view = "user.emails.event_invitation_email_template";
                $subject = 'Event Invitation Email';
                $mailData['event'] = $event;
                $this->sendMail($view, $request->email, ['mailData' => $mailData], $subject);
                $message = Lang::get('api.event_invite_message');
                return $this->sucessResponse(true, $message, []);
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Update the Event by event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date' => 'required|after:yesterday',
            'neighborhood' => 'required|exists:user_neighborhood,neighborhood_id',
            'category' => 'required|exists:event_categories,_id',
            'event_id' => 'required|exists:events,_id',
            'is_notify_rsvp' => 'required|in:0,1'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);

        $event = Event::find($request->event_id);

        if (isset($request->images)) {
            $validator = Validator::make($request->all(), [
                'images' => 'required|json'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $images = json_decode($request->images);
            foreach ($images as $img) {
                if (!empty($img->image_id)) {
                    $event_image = EventImage::find($img->image_id);
                    if($event_image){
                        $event_image->order_id = $img->order_id;
                        $event_image->save();
                    }                     
                } 
            }
        }

        $this->saveEvent($request, $event,'update');
        
        $message = Lang::get('api.event_update_message');
        return $this->sucessResponse(true, $message, $event);
    }

    /**
     * Save Event image by event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:events,_id',
                'type' => 'required|in:video,image',
                'file' => 'required',
                'order_id' => 'required',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $file = $request->file('file');
            return $this->uploadFile($request, $file, 'event', $request->event_id);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Delete the Event Image by image_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:event_images,_id',
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            $eventImage = EventImage::find($request->image_id);
            $imagePath['image'] = public_path('upload/events/' . $eventImage->eimg_image_file);
            $imagePath['video'] = public_path('upload/events/' . $eventImage->video_file);
            return $this->deleteFile($eventImage, $imagePath, 'event');
        }catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }

    /**
     * Get all event categories list
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventCategoriesList()
    {
        $eventCategory = EventCategory::get();
        $message = Lang::get('api.event_category_list_message');
        return $this->sucessResponse(true, $message, $eventCategory);
    }

    /**
     * Delete the Event by event_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,_id'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, []);
        $event = Event::find($request->event_id);
        if ($event) {
            $event->delete();
            $message = Lang::get('api.event_delete_message');
            return $this->sucessResponse(true, $message, []);
        }
    }

    /**
     * Delete Participant from event by eparticipant_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePaticipant(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'eparticipant_id' => 'required|exists:event_participants,_id'
            ]);
            if ($validator->fails()) return $this->validationResponse(false, $validator, []);
            $eparticipant = EventParticipant::find($request->eparticipant_id);
            $totalParticipant = $eparticipant->epart_children + $eparticipant->epart_adults;
            $event = Event::find($eparticipant->event_id);
            if ($eparticipant->participation_type == 'yes') {
                if ($event->event_total_joining > 0) {
                    $event->event_total_joining = $event->event_total_joining - $totalParticipant;
                }
            } elseif ($eparticipant->participation_type == 'maybe') {
                if ($event->event_total_maybe > 0) {
                    $event->event_total_maybe = $event->event_total_maybe - $totalParticipant;
                }
            }
            $this->updateParticipantCount($eparticipant->event_id);
            $event->save();
            $eparticipant->delete();
            $message = Lang::get('api.eparticipant_delete_message');
            return $this->sucessResponse(true, $message, []);
        }catch (\Exception $e) {
            return $this->exceptionResponse();
        }
    }
}
