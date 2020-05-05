<?php

namespace App\Observers;


use App\Models\PostReply;

/**
 * Class PostReplyObserver
 * @package App\Observers
 */
class PostReplyObserver {
    /**
     * @param PostReply $postReply
     */
    public function creating(PostReply $postReply)
    {
        $postReply->is_edited = 0;
    }


}
