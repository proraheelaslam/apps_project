<?php

namespace App\Observers;


use App\Models\Event;
use Illuminate\Support\Facades\File;

/**
 * Class EventObserver
 * @package App\Observers
 */
class EventObserver {

    /**
     * @param Event $event
     */
    public function deleting(Event $event)
    {
        foreach ($event->event_images()->get() as $image) {
            if (File::exists(public_path('upload/events/' . $image->eimg_image_file))) {
                File::delete(public_path('upload/events/' . $image->eimg_image_file));
                $image->delete();
            }
        }
        $event->participants()->delete();
    }
}
