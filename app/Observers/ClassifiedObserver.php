<?php

namespace App\Observers;

use App\Models\Classified;
use Illuminate\Support\Facades\File;

/**
 * Class ClassifiedObserver
 * @package App\Observers
 */
class ClassifiedObserver {


    public function creating(Classified $classified)
    {
        $classified->classified_sef_url = str_slug($classified->classified_title);
    }
    /**
     * @param Classified $classified
     */
    public function deleting(Classified $classified)
    {
        foreach ($classified->classified_images()->get() as $classified) {
            if (File::exists(public_path('upload/classifieds/' . $classified->cimg_image_file))) {
                File::delete(public_path('upload/classifieds/'. $classified->cimg_image_file));
            }
        }
    }
}
