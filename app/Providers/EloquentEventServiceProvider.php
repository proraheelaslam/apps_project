<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\Classified;
use App\Models\Event;
use App\Models\PostReply;
use App\Models\UserPost;
use App\Observers\BusinessObserver;
use App\Observers\ClassifiedObserver;
use App\Observers\EventObserver;
use App\Observers\PostObserver;
use App\Observers\PostReplyObserver;
use Illuminate\Support\ServiceProvider;

class EloquentEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        UserPost::observe(PostObserver::class);
        PostReply::observe(PostReplyObserver::class);
        Event::observe(EventObserver::class);
        Classified::observe(ClassifiedObserver::class);
        Business::observe(BusinessObserver::class);
    }
}
