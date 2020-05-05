<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/************User authentication ****/
Route::group(['prefix'=>'v1'],function (){

    Route::post('user/check-email','Api\V1\LoginApiController@checkEmail');
    Route::post('user/register','Api\V1\LoginApiController@create');
    Route::post('user/login','Api\V1\LoginApiController@login');
    Route::post('user/reset_password','Api\V1\UserApiController@resetPassword');
    Route::get('pages/{page_key}','Api\V1\PageApiController@index');
    Route::get('testing',function(){
        return 'test passed...';
    });

});

/*******END User authentication*******/


Route::group(['middleware'=>'auth:api','prefix'=>'v1'], function (){



    // verification
    Route::post('user/address/verify','Api\V1\LoginApiController@verifyAddress');
    Route::post('user/code/verify','Api\V1\LoginApiController@codeVerify');

    // neighborhood
    Route::post('neighborhood/create','Api\V1\NeighborhoodApiController@create');
    Route::get('neighborhood/search','Api\V1\NeighborhoodApiController@search');
    Route::post('neighborhood/join','Api\V1\NeighborhoodApiController@joinNeighborhood');
    Route::get('neighborhood/users/{paginate}','Api\V1\NeighborhoodApiController@neighborhoodUserLists');
    Route::post('user/update_profile','Api\V1\UserApiController@updateProfile');
    Route::post('neighborhood/make_favorite','Api\V1\NeighborhoodApiController@neighborhoodFavorite');
    Route::post('neighborhood/leave','Api\V1\NeighborhoodApiController@leaveNeighborhood');

    Route::post('user/deactivate','Api\V1\UserApiController@deactivateAccount');
    // setting
    Route::post('user/update_setting','Api\V1\UserApiController@updateAppSetting');
    Route::get('user/settings','Api\V1\UserApiController@appSettingList');
    Route::get('user/app_settings','Api\V1\UserApiController@userApplicationSettingList');
    Route::get('users/{id}','Api\V1\UserApiController@getUserDetail');
    // post categories
    Route::get('post/categories','Api\V1\CategoryApiController@index');
    // posts
    Route::get('posts','Api\V1\PostApiController@index');
    Route::get('posts/test','Api\V1\PostApiController@index2');

    Route::post('post/create','Api\V1\PostApiController@create');


    Route::post('post/creates','Api\V1\PostApiController@creates');




    Route::post('post/update','Api\V1\PostApiController@update');
    Route::post('post/delete','Api\V1\PostApiController@destroy');
    Route::get('post/{id}','Api\V1\PostApiController@postDetail');
    Route::post('post/save_image','Api\V1\PostApiController@saveImage');
    Route::post('post/delete_image','Api\V1\PostApiController@deleteImage');
    // comments and thanks on comments
    Route::post('post/comments','Api\V1\PostApiController@storePostComment');
    Route::get('post/comments/{post_id}','Api\V1\PostApiController@postCommentList');
    Route::post('post/thanks','Api\V1\PostApiController@postThanks');
    Route::get('post/thanks/{post_id}','Api\V1\PostApiController@postThanksUsers');
    Route::post('post/comment/update','Api\V1\PostApiController@updateComment');
    Route::post('post/delete_comment','Api\V1\PostApiController@deleteComment');

    //comments and thanx on post images
    Route::post('media/comments','Api\V1\PostMediaApiController@storeMediaComment');
    Route::get('media/comments/{post_id}/{media_id}','Api\V1\PostMediaApiController@mediaCommentList');
    Route::post('media/thanks','Api\V1\PostMediaApiController@mediaThanks');
    Route::get('media/thanks/{post_id}/{media_id}','Api\V1\PostMediaApiController@mediaThanksUsers');
    // post answers
    Route::post('question/answer','Api\V1\PostApiController@postAnswer');
    Route::post('question/delete','Api\V1\PostApiController@deleteQuestion');

    //events categories
    Route::get('events/categories','Api\V1\EventApiController@eventCategoriesList');
    // events
    Route::post('events/create','Api\V1\EventApiController@create');
    Route::get('events','Api\V1\EventApiController@index');
    Route::post('events/save_image','Api\V1\EventApiController@saveImage');
    Route::post('events/delete_event','Api\V1\EventApiController@destroy');
    Route::post('events/delete_image','Api\V1\EventApiController@deleteImage');
    Route::get('events/detail/{id}','Api\V1\EventApiController@eventDetail');
    Route::post('events/update','Api\V1\EventApiController@update');
    Route::post('events/join','Api\V1\EventApiController@joinEvent');
    Route::post('events/invite','Api\V1\EventApiController@inviteEvent');
    Route::post('events/join/list','Api\V1\EventApiController@joinEventList');
    Route::post('events/delete_participant','Api\V1\EventApiController@deletePaticipant');


    //comments and thanx on event images
    Route::post('event/media/comments','Api\V1\EventMediaApiController@storeMediaComment');
    Route::get('event/media/comments/{event_id}/{media_id}','Api\V1\EventMediaApiController@mediaCommentList');
    Route::post('event/media/thanks','Api\V1\EventMediaApiController@mediaThanks');
    Route::get('event/media/thanks/{event_id}/{media_id}','Api\V1\EventMediaApiController@mediaThanksUsers');
    Route::post('event/comment/update','Api\V1\EventMediaApiController@updateComment');
    Route::post('event/delete_comment','Api\V1\EventMediaApiController@deleteComment');
    // classifieds
    Route::post('classifieds/create','Api\V1\ClassifiedApiController@create');
    Route::get('classifieds','Api\V1\ClassifiedApiController@index');
    Route::post('classifieds/save_image','Api\V1\ClassifiedApiController@saveImage');
    Route::post('classifieds/delete_classified','Api\V1\ClassifiedApiController@destroy');
    Route::post('classifieds/delete_image','Api\V1\ClassifiedApiController@deleteImage');
    Route::get('classifieds/detail/{id}','Api\V1\ClassifiedApiController@classifiedDetail');
    Route::post('classifieds/update','Api\V1\ClassifiedApiController@update');
    Route::get('classifieds/category/{product_id}','Api\V1\ClassifiedApiController@categoryClassifieds');
    // offer
    Route::post('classifieds/offer/create','Api\V1\ClassifiedOfferApiController@create');
    // classified categories
    Route::get('classifieds/categories','Api\V1\ClassifiedApiController@classifiedCategoriesList');

    //comments and thanx on classified images
    Route::post('classifieds/media/comments','Api\V1\ClassifiedMediaApiController@storeMediaComment');
    Route::get('classifieds/media/comments/{classified_id}/{media_id}','Api\V1\ClassifiedMediaApiController@mediaCommentList');

    Route::post('classifieds/media/thanks','Api\V1\ClassifiedMediaApiController@mediaThanks');
    Route::get('classifieds/media/thanks/{classified_id}/{media_id}','Api\V1\ClassifiedMediaApiController@mediaThanksUsers');
    Route::post('classifieds/comment/update','Api\V1\ClassifiedMediaApiController@updateComment');
    Route::post('classifieds/delete_comment','Api\V1\ClassifiedMediaApiController@deleteComment');

    // business
    Route::get('business','Api\V1\BusinessApiController@index');
    Route::post('business/create','Api\V1\BusinessApiController@create');
    Route::post('business/update','Api\V1\BusinessApiController@update');
    Route::post('business/delete_business','Api\V1\BusinessApiController@destroy');
    Route::get('business/detail/{id}','Api\V1\BusinessApiController@businessDetail');
    Route::post('business/save_image','Api\V1\BusinessApiController@saveImage');
    Route::post('business/delete_image','Api\V1\BusinessApiController@deleteImage');
    Route::post('business/recommend','Api\V1\BusinessApiController@recommendBusiness');
    Route::post('business/likes','Api\V1\BusinessApiController@businessLikes');
    Route::get('business/categories','Api\V1\BusinessApiController@businessCategoriesList');

    Route::post('business/recommended/users','Api\V1\BusinessApiController@businessRecommendedUsers');

    Route::post('business/report','Api\V1\BusinessApiController@businessReport');
    Route::get('business/report_reasons','Api\V1\BusinessApiController@businessReportReasons');

    Route::get('business/{category_id}','Api\V1\BusinessApiController@businessCategoryFilter');

    //comments and thanx on business images
    Route::post('business/media/comments','Api\V1\BusinessMediaApiController@storeMediaComment');
    Route::get('business/media/comments/{business_id}/{media_id}','Api\V1\BusinessMediaApiController@mediaCommentList');
    Route::post('business/media/thanks','Api\V1\BusinessMediaApiController@mediaThanks');
    Route::get('business/media/thanks/{business_id}/{media_id}','Api\V1\BusinessMediaApiController@mediaThanksUsers');
    Route::post('business/comment/update','Api\V1\BusinessMediaApiController@updateComment');
    Route::post('business/delete_comment','Api\V1\BusinessMediaApiController@deleteComment');
    
    // push notification
    Route::post('device/save_status','Api\V1\UserApiController@saveDeviceStatus');
    // notification
    Route::get('notification/list','Api\V1\NotificationApiController@notificationList');
    Route::post('device/logout_device','Api\V1\NotificationApiController@logoutDevice');
    Route::post('notification/unread_notifications','Api\V1\NotificationApiController@getUnreadNotifications');

    Route::post('chat/upload_file','Api\V1\ChatApiController@saveFile');








});
Route::get('users/birthday','Api\V1\CronjobApiController@sendBirthdayNotificaiton');
Route::group(['prefix'=>'v1'], function () {
    Route::post('chat/send_notification', 'Api\V1\ChatApiController@sendChatNotification');
});



