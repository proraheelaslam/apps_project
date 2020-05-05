<?php

namespace App\Observers;

use App\Models\Business;
use Illuminate\Support\Facades\File;

/**
 * Class BusinessObserver
 * @package App\Observers
 */
class BusinessObserver {


    public function creating(Business $business)
    {

        $business->business_sef_url = str_slug($business->business_name);
    }
    /**
     * @param Business $business
     */
    public function deleting(Business $business)
    {
        foreach ($business->business_images()->get() as $image) {
            if (File::exists(public_path('upload/businesses/' . $image->bimg_name)) || File::exists(public_path('upload/businesses/' . $image->video_file))) {
                File::delete(public_path('upload/businesses/' . $image->bimg_name));
                File::delete(public_path('upload/businesses/' . $image->video_file));
                $image->delete();
            }
        }
        $business->business_recommended()->delete();
    }
}
