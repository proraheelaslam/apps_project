<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */
namespace App\Traits;

use App\Models\PostAnswer;
use App\Models\PostQuestion;
use App\Models\PostThank;
use App\Models\User;
use App\Models\UserNeighborhood;
use App\Models\UserPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

/**
 * Trait UserStatusTrait
 * @package App\Traits
 */
trait UserStatusTrait
{
    /**
     * Get All approved users
     * @param $neighbourhoodId
     * @return \Illuminate\Support\Collection
     */
    public function approvedUsers($neighbourhoodId)
    {
        $users = User::with(['neighborhoods'=>function($query) use($neighbourhoodId) {
            $query->where('neighborhood_id',$neighbourhoodId);
        }]);
        $result = $users->whereHas('status',function($q){
            $q->where('ustatus_name','approved');
        });
        return $result->pluck('_id');
    }




}