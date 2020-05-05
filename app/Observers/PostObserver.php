<?php

namespace App\Observers;


use App\Models\UserPost;
use Illuminate\Support\Facades\File;

/**
 * Class PostObserver
 * @package App\Observers
 */
class PostObserver {
    /**
     * @param UserPost $post
     */
    public function creating(UserPost $post)
    {
        $post->is_edited = 0;
    }

    /**
     * @param UserPost $post
     */
    public function deleting(UserPost $post)
    {
        $post->post_questions()->delete();
        $post->replies()->delete();
        $post->thanks()->delete();
        foreach ($post->post_images()->get() as $image) {
            if (File::exists(public_path('upload/posts/' . $image->pimg_image_file)) || File::exists(public_path('upload/posts/' . $image->video_file))) {
                File::delete(public_path('upload/posts/' . $image->pimg_image_file));
                File::delete(public_path('upload/posts/' . $image->video_file));
                $image->delete();
            }
        }
    }
}
