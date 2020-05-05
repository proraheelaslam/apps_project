<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('admin')->user();


    return view('admin.home');
})->name('home');

//    Route::group(['middleware'=>['auth.lock']], function (){
        // users

        Route::post('/user/toggle_collapse', 'Admin\UserController@toggleCollapse');

        Route::get('/all_users/list/get', 'Admin\UserController@getAjaxUsers')->name('users.getAjaxUsers');
        Route::get('/password/change', 'AdminAuth\ChangePasswordController@edit')->name('password.change');
        Route::post('/password/change', 'AdminAuth\ChangePasswordController@update')->name('password.update');
        Route::get('/users', 'Admin\UserController@index')->name('users.list');
        Route::get('/users/{id}', 'Admin\UserController@show')->name('users.show');
        Route::post('/user/address/verify', 'Admin\UserController@userAddressVerify')->name('user.userAddressVerify');

        Route::post('/user/profile/update', 'Admin\UserController@updateProfile')->name('user.updateProfile');

        Route::post('/user/update_approve_status', 'Admin\UserController@updateApproveStatus')->name('user.updateApproveStatus');

        // chat
        Route::get('/chat/get/user/{userId}', 'Admin\ChatController@getChatthread')->name('user.getChatthread');
        Route::get('/chat/{chatId}/user/{userId}', 'Admin\ChatController@getChatDetail')->name('user.getChatDetail');
        Route::post('/chat/message/delete', 'Admin\ChatController@deleteChatMessage')->name('user.deleteChatMessage');



        Route::post('/user/delete_profile', 'Admin\UserController@deleteProfile')->name('user.deleteProfile');
        Route::post('/user/delete_address_image', 'Admin\UserController@deleteAddressImage')->name('user.deleteAddressImage');

        Route::post('/user/send_message', 'Admin\UserController@sendMessage')->name('user.sendMessage');
        //Neighborhoods
        Route::get('neighborhoods', 'Admin\NeighborhoodController@index')->name('neighborhoods.list');
        Route::get('neighborhoods/get', 'Admin\NeighborhoodController@getAjaxNeighborhoods')->name('neighborhoods.getAjaxNeighborhoods');
        Route::get('neighborhoods/{id}', 'Admin\NeighborhoodController@show')->name('neighborhoods.show');
        Route::get('neighborhoods/edit/{id}', 'Admin\NeighborhoodController@edit')->name('neighborhoods.edit');
        Route::post('neighborhoods/verify', 'Admin\NeighborhoodController@neighborhoodVerify')->name('neighborhoods.neighborhoodVerify');
        Route::post('neighborhoods/neighborhood_update', 'Admin\NeighborhoodController@neighborhoodUpdate')->name('neighborhoods.neighborhoodUpdate');
        Route::get('neighborhoods/{id}/users', 'Admin\NeighborhoodController@neighborhoodUsers')->name('neighborhoods.neighborhoodUsers');
        Route::delete('neighborhoods/user/delete/{neighbourhoodId}/{userId}', 'Admin\NeighborhoodController@deleteNeighbourhoodUser');
        Route::get('neighborhoods/users/get/{id}', 'Admin\NeighborhoodController@getAjaxNeighborhoodUsers')->name('neighborhoods.getAjaxNeighborhoodUsers');

        // post categories
        Route::get('post/categories', 'Admin\PostCategoryController@index')->name('post.category.list');
        Route::get('post/categories/get', 'Admin\PostCategoryController@getAjaxPostCategories')->name('post.category.getAjaxPostCategories');

        // resource post category
        Route::resource('post/categories','Admin\PostCategoryController');

        // pages
        Route::get('pages', 'Admin\PageController@index')->name('pages.list');
        Route::get('pages/get', 'Admin\PageController@getAjaxPostContentPages')->name('pages.getAjaxPostContentPages');
        Route::resource('pages','Admin\PageController');

        // email templates
        Route::get('emails', 'Admin\EmailTemplateController@index')->name('emails.list');
        Route::get('emails/get', 'Admin\EmailTemplateController@getAjaxEmails')->name('emails.getAjaxEmails');
        Route::resource('emails','Admin\EmailTemplateController');

        // posts
        Route::get('poll_posts', 'Admin\PostController@pollPostsIndex')->name('poll_posts.list');
        Route::get('alert_posts', 'Admin\PostController@alertPostsIndex')->name('alert_posts.list');
        Route::get('message_posts', 'Admin\PostController@messagePostsIndex')->name('message_posts.list');

        Route::get('posts/get', 'Admin\PostController@getAjaxPosts')->name('posts.getAjaxPosts');
        //
        Route::get('posts/{id}', 'Admin\PostController@show')->name('posts.show');

        Route::get('posts/edit/{id}', 'Admin\PostController@edit')->name('posts.edit');

        // Route::put('posts/update/{id}/{postType}', 'Admin\PostController@update')->name('posts.update');
        Route::put('posts/update_poll_posts/{id}', 'Admin\PostController@updatePollPosts')->name('posts.update_poll_posts');
        Route::put('posts/update_alert_posts/{id}', 'Admin\PostController@updateAlertPosts')->name('posts.update_alert_posts');
        Route::put('posts/update_message_posts/{id}', 'Admin\PostController@updateMessagePosts')->name('posts.update_message_posts');

        Route::delete('posts/{id}', 'Admin\PostController@destroy')->name('posts.destroy');

        /******************************  START EVENT ROUTES ***************************************/
        
        Route::get('events', 'Admin\EventController@index')->name('events.list');
        Route::get('events/get', 'Admin\EventController@getAjaxEvents')->name('events.getAjaxEvents');
        Route::get('events/{eventId}', 'Admin\EventController@show')->name('events.show');

        Route::get('events/edit/{eventId}', 'Admin\EventController@edit')->name('events.edit');
        Route::put('events/{eventId}', 'Admin\EventController@update')->name('events.update');
        Route::delete('events/{eventId}', 'Admin\EventController@destroy')->name('events.destroy');

        Route::post('event/participants/delete', 'Admin\EventController@deleteParticipant')->name('event.deleteParticipant');
        Route::post('event/media/delete', 'Admin\EventController@deleteMedia')->name('event.deleteMedia');

        /******************************  END EVENT ROUTES ***************************************/

        /******************************  START EVENT CATEGORY ROUTES ***************************************/
        
        Route::get('event_categories/add', 'Admin\EventCategoryController@create')->name('event_category.add');
        Route::post('event_categories/add', 'Admin\EventCategoryController@store')->name('event_category.create');

        Route::get('event_categories', 'Admin\EventCategoryController@index')->name('event_category.list');
        Route::get('event_categories/get', 'Admin\EventCategoryController@getAjaxEventCategories')->name('event_category.getAjaxEventCategories');
        Route::get('event_categories/{eventCategoryId}', 'Admin\EventCategoryController@show')->name('event_category.show');

        Route::get('event_categories/edit/{eventCategoryId}', 'Admin\EventCategoryController@edit')->name('event_category.edit');
        Route::put('event_categories/{eventCategoryId}', 'Admin\EventCategoryController@update')->name('event_category.update');
        Route::delete('event_categories/{eventCategoryId}', 'Admin\EventCategoryController@destroy')->name('event_category.destroy');


        /******************************  END EVENT CATEGORY ROUTES ***************************************/

        /******************************  START BUSINESS CATEGORY ROUTES ***************************************/
        
        Route::get('business_categories/add', 'Admin\BusinessCategoryController@create')->name('business_category.add');
        Route::post('business_categories/add', 'Admin\BusinessCategoryController@store')->name('business_category.create');

        Route::get('business_categories', 'Admin\BusinessCategoryController@index')->name('business_category.list');
        Route::get('business_categories/get', 'Admin\BusinessCategoryController@getAjaxBusinessCategories')->name('business_category.getAjaxBusinessCategories');
        Route::get('business_categories/{eventCategoryId}', 'Admin\BusinessCategoryController@show')->name('business_category.show');

        Route::get('business_categories/edit/{eventCategoryId}', 'Admin\BusinessCategoryController@edit')->name('business_category.edit');
        Route::put('business_categories/{eventCategoryId}', 'Admin\BusinessCategoryController@update')->name('business_category.update');
        Route::delete('business_categories/{eventCategoryId}', 'Admin\BusinessCategoryController@destroy')->name('business_category.destroy');


        /******************************  END BUSINESS CATEGORY ROUTES ***************************************/

        /******************************  START BUSINESSES ROUTES ***************************************/
        Route::get('businesses', 'Admin\BusinessesController@index')->name('businesses.list');
        Route::get('businesses/get', 'Admin\BusinessesController@getAjaxBusiness')->name('businesses.getAjaxBusiness');
        Route::get('business/{id}', 'Admin\BusinessesController@show')->name('business.show');

        Route::get('business/edit/{id}', 'Admin\BusinessesController@edit')->name('business.edit');
        Route::put('business/{id}', 'Admin\BusinessesController@update')->name('business.update');
        Route::delete('business/{id}', 'Admin\BusinessesController@destroy')->name('business.destroy');

        // Route::post('event/participants/delete', 'Admin\EventController@deleteParticipant')->name('event.deleteParticipant');
        Route::post('business/media/delete', 'Admin\BusinessesController@deleteMedia')->name('business.deleteMedia');
        Route::post('business/recommendation/delete', 'Admin\BusinessesController@deleteRecommendation')->name('business.deleteRecommendation');
        Route::post('business/like/delete', 'Admin\BusinessesController@deleteLike')->name('business.deleteLike');
        Route::post('business/update_approve_status', 'Admin\BusinessesController@updateApproveStatus')->name('business.updateApproveStatus');
        Route::post('business/report/delete', 'Admin\BusinessesController@deleteReport')->name('business.deleteReport');
        /******************************  END BUSINESSES ROUTES ***************************************/

        /******************************  START BUSINESS REPORT REASON ROUTES ***************************************/
        
        Route::get('business_report_reason/add', 'Admin\BusinessReportReasonController@create')->name('business_report_reason.add');
        Route::post('business_report_reason/add', 'Admin\BusinessReportReasonController@store')->name('business_report_reason.create');

        Route::get('business_report_reason', 'Admin\BusinessReportReasonController@index')->name('business_report_reason.list');
        Route::get('business_report_reason/get', 'Admin\BusinessReportReasonController@getAjaxBusinessReportCategories')->name('business_category.getAjaxBusinessReportCategories');
        Route::get('business_report_reason/{businessReportReasonId}', 'Admin\BusinessReportReasonController@show')->name('business_report_reason.show');

        Route::get('business_report_reason/edit/{businessReportReasonId}', 'Admin\BusinessReportReasonController@edit')->name('business_report_reason.edit');
        Route::put('business_report_reason/{businessReportReasonId}', 'Admin\BusinessReportReasonController@update')->name('business_report_reason.update');
        Route::delete('business_report_reason/{businessReportReasonId}', 'Admin\BusinessReportReasonController@destroy')->name('business_report_reason.destroy');


        /******************************  END BUSINESS REPORT REASON ROUTES ***************************************/

        /******************************  START CLASSIFIED CATEGORY ROUTES ***************************************/
        
        Route::get('classified_category', 'Admin\ClassifiedCategoryController@index')->name('classified_category.list');
        Route::get('classified_category/get', 'Admin\ClassifiedCategoryController@getAjaxCategories')->name('classified_category.getAjaxClassifieds');
        Route::get('classified_category/{categorydId}', 'Admin\ClassifiedCategoryController@show')->name('classified_category.show');
        Route::get('classified_category/edit/{categoryId}', 'Admin\ClassifiedCategoryController@edit')->name('classified_category.edit');
        Route::put('classified_category/{categoryId}', 'Admin\ClassifiedCategoryController@update')->name('classified_category.update');

        Route::delete('classified_category_delete/{categoryId}', 'Admin\ClassifiedCategoryController@destroy')->name('classified_category.destroy');
        /******************************  END CLASSIFIED CATEGORY ROUTES ***************************************/

        /******************************  START CLASSIFIED ROUTES ***************************************/
        
        Route::get('classifieds', 'Admin\ClassifiedController@index')->name('classifieds.list');
        Route::get('classifieds/get', 'Admin\ClassifiedController@getAjaxClassifieds')->name('classifieds.getAjaxClassifieds');
        Route::get('classifieds/{classifiedId}', 'Admin\ClassifiedController@show')->name('classifieds.show');

        Route::get('classifieds/edit/{classifiedId}', 'Admin\ClassifiedController@edit')->name('classifieds.edit');
        Route::put('classifieds/{classifiedId}', 'Admin\ClassifiedController@update')->name('classifieds.update');
        Route::delete('classifieds/{classifiedId}', 'Admin\ClassifiedController@destroy')->name('classifieds.destroy');

        Route::post('classified/media/delete', 'Admin\ClassifiedController@deleteMedia')->name('classified.deleteMedia');
        Route::post('classified/offer/delete', 'Admin\ClassifiedController@deleteOffer')->name('classified.deleteOffer');

        /******************************  END CLASSIFIED ROUTES ***************************************/

        /******************************  START APP notification ROUTES ***************************************/

        Route::get('app_notification', 'Admin\AppNotificationController@index')->name('app_notification.list');
        Route::get('app_notification/get', 'Admin\AppNotificationController@getAjaxAppNotifications')->name('app_notification.getAjaxClassifieds');
        Route::get('app_notification/{appNotificationId}', 'Admin\AppNotificationController@show')->name('app_notification.show');
        Route::get('app_notification/edit/{appNotificationId}', 'Admin\AppNotificationController@edit')->name('app_notification.edit');
        Route::put('app_notification/{appNotificationId}', 'Admin\AppNotificationController@update')->name('app_notification.update');

        /******************************  END CLASSIFIED CATEGORY ROUTES ***************************************/

        /******************************  START APP Setting ROUTES ***************************************/

        Route::get('app_settings', 'Admin\ApplicationSettingController@index')->name('app_settings.list');
        Route::get('app_settings/get', 'Admin\ApplicationSettingController@getAjaxAppSetting')->name('app_settings.getAjaxClassifieds');
        Route::get('app_settings/edit/{appNotificationId}', 'Admin\ApplicationSettingController@edit')->name('app_settings.edit');
        Route::put('app_settings/{appNotificationId}', 'Admin\ApplicationSettingController@update')->name('app_settings.update');

        Route::get('app_settings/create', 'Admin\ApplicationSettingController@create')->name('app_settings.add');
        Route::post('app_settings/create', 'Admin\ApplicationSettingController@store')->name('app_settings.create');

        /******************************  END APP Setting ROUTES ***************************************/


        /******************************  START APP Setting ROUTES ***************************************/

        Route::get('app/push_notifications', 'Admin\AppPushNotificationController@index')->name('push_notification.list');
        Route::get('app/push_notifications/get', 'Admin\AppPushNotificationController@getAjaxPushNotification')->name('push_notification.getAjaxPushNotification');
        Route::get('app/push_notifications/edit/{appNotificationId}', 'Admin\AppPushNotificationController@edit')->name('push_notification.edit');
        Route::put('app/push_notifications/{appNotificationId}', 'Admin\AppPushNotificationController@update')->name('app.push_notification.update');

        Route::get('app/push_notifications/create', 'Admin\AppPushNotificationController@create')->name('push_notification.add');
        Route::post('app/push_notifications/create', 'Admin\AppPushNotificationController@store')->name('push_notification.create');

        /******************************  END APP Setting ROUTES ***************************************/
        /************************/
        /*********************Start Ads ROUTES ***********************************************/
        Route::get('ads', 'Admin\AdsController@index')->name('ads.list');
        Route::get('ads/get', 'Admin\AdsController@getAjaxAds')->name('ads.getAjaxAds');
        Route::resource('ads','Admin\AdsController');

        /*****************End Ads ROUTES*******************************************************/



        // import neighborhood -------------------------------------------------------------
        Route::get('neighborhood/export/create', 'Admin\NeighborhoodController@createNeighborhoodExport')->name('neighborhoods.createNeighborhoodExport');
        Route::post('neighborhood/export', 'Admin\NeighborhoodController@neighborhoodExport')->name('neighborhoods.neighborhoodExport');
        Route::post('neighborhood/export', 'Admin\NeighborhoodController@neighborhoodExport')->name('neighborhoods.neighborhoodExport');

        // roles and premission routes

        Route::resource('roles', 'Admin\RoleController',['except' => ['show']]);
        Route::get('roles/get', 'Admin\RoleController@getAjaxRoles')->name('roles.getAjaxRoles');
        Route::get('roles/get-permissions/{id}', 'Admin\RoleController@getRolePermission')->name('roles.getRolePermission');
        Route::put('roles/update-permission/{id}', 'Admin\RoleController@updateRolePermission')->name('roles.updateRolePermission');
        Route::resource('permissions', 'Admin\PermissionController',['except' => ['show']]);
        Route::get('permissions/get', 'Admin\PermissionController@getAjaxPermissions')->name('permissions.getAjaxPermissions');

        // managers
        Route::resource('managers', 'Admin\ManagerController',['except' => ['show']]);
        Route::get('managers/get','Admin\ManagerController@getAjaxManagers')->name('managers.getAjaxManagers');

        


        // comments
        Route::post('post/comment/delete', 'Admin\PostController@deleteComment')->name('post.comment.deleteComment');
        Route::post('post/thank/user/delete', 'Admin\PostController@deleteThankUser')->name('post.thank.deleteThankUser');
        Route::post('post/image/delete', 'Admin\PostController@deletePostImage')->name('post.deletePostImage');
        Route::post('post/user_answer/delete', 'Admin\PostController@deleteUserAnswer')->name('post.userAnswer.delete');

        Route::post('post/comment/get', 'Admin\PostController@getComment')->name('post.comment.getComment');
        Route::post('post/comment/update', 'Admin\PostController@updateComment')->name('post.comment.updateComment');


        // country, city dropdown --------------------------------------------------

        Route::get('test/{countryId}','Admin\LocationDropDownController@test');
        Route::get('country/get','Admin\LocationDropDownController@getCountries')->name('country.getAjaxCountries');
        Route::get('states/get/{countryId}','Admin\LocationDropDownController@getStates')->name('country.getAjaxStates');
        Route::get('cities/get/{stateId}','Admin\LocationDropDownController@getCities')->name('country.getAjaxStates');
        // admin profile
        Route::get('profile', 'Admin\AdminController@create')->name('admin.create');
        Route::post('profile/update', 'Admin\AdminController@updateProfile')->name('admin.updateProfile');


        // delete data

        Route::get('delete_all_data','Admin\AdminController@removeAllData');


        Route::get('db/backup','Admin\DatabaseBackupController@index');
        Route::get('db/backup/dump','Admin\DatabaseBackupController@dumpMongoDatabase');


//    });





//Route::resource('posts','Admin\PostCategoryController');




