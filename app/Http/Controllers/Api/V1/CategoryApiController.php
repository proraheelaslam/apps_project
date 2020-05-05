<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PostCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;


class CategoryApiController extends Controller
{
    use ApiResponse;

    /**
     * Get Category list
     * Note: Not used currently
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $postCategories = PostCategory::get();
        $message = Lang::get('api.post_category_message');
        return $this->sucessResponse(true, $message, $postCategories);

    }
}
