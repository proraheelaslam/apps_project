<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Classified;
use App\Models\ClassifiedOffer;
use App\Models\EmailTemplate;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;


class ClassifiedOfferApiController extends Controller
{
    use  ApiResponse;

    /**
     * Create classified/Product Offer by classified_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classified_id' => 'required|exists:classifieds,_id',
            'price' => 'required|numeric|min:0',
            'comment' => 'required'
        ]);
        if ($validator->fails()) return $this->validationResponse(false, $validator, []);
        if (!$this->checkUserStatus(Auth::id())) {
            $message = Lang::get('api.status_not_approved_message');
            return $this->errorResponse(false, $message, []);
        }

        $offer = ClassifiedOffer::where('classified_id',$request->classified_id)->where('user_id',Auth::id())->first();
        if ($offer) {
            ClassifiedOffer::where('classified_id',$request->classified_id)->where('user_id',Auth::id())->delete();
        }

        $classfiedDetail = Classified::find($request->classified_id)->first();
        $productName = $classfiedDetail->classified_title;
        $productPrice= $classfiedDetail->classified_price;
        $username = $classfiedDetail->users->full_name;
        $userEmail  = $classfiedDetail->users->user_email;
        $userBy  = Auth::user()->full_name;

        $classifiedOffer = new ClassifiedOffer();
        $classifiedOffer->classified_id = $request->classified_id;
        $classifiedOffer->user_id = Auth::id();
        $classifiedOffer->coffer_price = $request->price;
        $classifiedOffer->coffer_comments = $request->comment;
        $classifiedOffer->save();
        // send email to product owner
        $user = Auth::user();
        $emailTemplate = EmailTemplate::where('key', 'product_offer')->first();
        $view = "user.emails.product_offer_email_template";
        $emailTemplate->content = str_replace(['{username}','{user_by}','{product_name}','{offer_price}','{offer_comment}'], [$username,$userBy,
            $productName,$productPrice,$classifiedOffer->coffer_comments], $emailTemplate->content);
        $mailData['content'] = $emailTemplate->content;
        $this->sendMail($view, $userEmail, ['mailData' => $mailData], $emailTemplate->subject);
        $message = Lang::get('api.create_offer_message');
        return $this->sucessResponse(true,$message,[]);
    }
}
