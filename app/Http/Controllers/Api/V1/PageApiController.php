<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Page;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class PageApiController extends Controller
{
    use ApiResponse;

    /**
     * Get page data by page_key
     * @param $pageKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($pageKey)
    {

        $pages = Page::where('page_key',$pageKey)->first();
        $message = Lang::get('api.page_message');
        return $this->sucessResponse(true, $message, $pages);
    }
}
