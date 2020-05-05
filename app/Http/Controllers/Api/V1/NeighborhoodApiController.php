<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\NeighbourhoodNotification;
use App\Models\Chatthreads;
use App\models\City;
use App\models\Country;
use App\Models\Neighborhood;
use App\models\State;
use App\Models\User;
use App\Models\UserNeighborhood;
use App\Models\UserNeighborhoodFavorite;
use App\Models\Device;
use App\Traits\GuzzleApiCallTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Models\UserStatus;

class NeighborhoodApiController extends Controller
{
    use ApiResponse, GuzzleApiCallTrait;

    public function __construct()
    {

    }

    /**
     * Create new neighbbourhood
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'neighborhood_name' => 'required',
                'neighborhood_address' => 'required',
                'neighborhood_area' => 'required',

            ]);
            $areaArray = json_decode($request->neighborhood_area, true);
            $areaArray[] = $areaArray[0];
            $areaUniqureArray = array_unique($areaArray, SORT_REGULAR);
            if ($validator->fails()) return $this->validationResponse(false, $validator, $data = []);
            if (!$this->checkUserStatus(Auth::id())) {
                $message = Lang::get('api.status_not_approved_message');
                return $this->errorResponse(false, $message, []);
            }
            $addressAreaFormat = [
                'coordinates' => [$areaArray],
                'type' => 'Polygon'
            ];
            $where = ["neighborhood_area" => ['$near' => ['$geometry' =>
                ['type' => 'Polygon', 'coordinates' => $areaUniqureArray[0]
                ]]
            ]];
            //$neighbourhoodArea = Neighborhood::whereRaw($where)->get();
            //if (count($neighbourhoodArea) == 0) {
            $input = $request->all();
            $latlong = (float)Auth::user()->user_address_latitude . ',' . (float)Auth::user()->user_address_longitude;
//            try{
                $country_id = 0;
                $state_id = 0;
                $city_id = 0;
                try {
                    $url = 'https://maps.googleapis.com/maps/api/geocode/json' . '?latlng=' . $latlong . '&key=' . 'AIzaSyDHH2WyrHbuChuvGc1zkbY3LwiODEF8zGI' . '&sensor=false';
                    // Google api call
                    $mapData = $this->get($url);
                    $mapData = json_decode($mapData);
                    $city = 0;
                    $country = 0;
                    $state = 0;
                if (!empty($mapData->results)) {
                    $addressComponentArr = $mapData->results[0]->address_components;
                    foreach ($addressComponentArr as $address) {
                        if ($address->types[0] == 'administrative_area_level_2') {
                            $city = $address->long_name;
                        }
                        if ($address->types[0] == 'administrative_area_level_1') {
                            $state = $address->long_name;
                        }
                        if ($address->types[0] == 'country') {
                            $country = $address->long_name;
                        }
                    }
                    $countryName = Country::where('name', $country)->first();
                    $maxCountry = (int)Country::max('country_id');
                    if ($countryName) {
                        $country_id = (int)$countryName->country_id;
                    } else {
                        $countryObj = new Country();
                        $countryObj->name = $country;
                        $countryObj->phoneCode = 0;
                        $countryObj->sortname = $country;
                        $countryObj->country_id = $maxCountry + 1;
                        $countryObj->save();
                        $country_id = (int)$countryObj->country_id;
                    }
                    $stateName = State::where('name', $state)->where('country_id', (string)$country_id)->first();
                    $maxState = (int)State::max('state_id');
                    if ($stateName) {
                        $state_id = (int)$stateName->state_id;
                    } else {
                        $stateObj = new State();
                        $stateObj->name = $state;
                        $stateObj->phoneCode = 0;
                        $stateObj->sortname = $state;
                        $stateObj->state_id = (string)$maxState + 1;
                        $stateObj->country_id = (int)$country_id;
                        $stateObj->save();
                        $state_id = (int)$stateObj->state_id;
                    }
                    $cityName = City::where('name', $city)->where('state_id', (string)$state_id)->first();
                    $maxCity = (int)City::max('city_id');
                    if ($cityName) {
                        $city_id = (int)$cityName->city_id;
                    } else {
                        $cityObj = new City();
                        $cityObj->name = $city;
                        $cityObj->phoneCode = 0;
                        $cityObj->sortname = $city;
                        $cityObj->city_id = (string)$maxCity + 1;
                        $cityObj->state_id = (string)$state_id;
                        $cityObj->save();
                        $city_id = (int)$cityObj->city_id;
                    }
                }
                }catch (\Exception $e) {
                    return response()->json(['status' => false, 'message' => 'Selected Area State and city are out of range']);
                }
                $input['neighborhood_area'] = $addressAreaFormat;
                $input['created_by'] = Auth::user()->id;
                $input['verified_by_admin'] = 0;
                $input['neighborhood_total_users'] = 1;
                $input['country_id'] = $country_id;
                $input['state_id'] = $state_id;
                $input['city_id'] = $city_id;
                $input['neighborhood_sef_url'] = str_slug($input['neighborhood_name']);
                $input['nstatus_id'] = $this->neighborhoodStatusId('Pending');
                // save neighborhood
                $neighbourhood = Neighborhood::create($input);
                // save user id and neighborhood id in UserNeighbourhood Collection
                $userNeighborhood = new UserNeighborhood();
                $userNeighborhood->user_id = Auth::user()->id;
                $userNeighborhood->neighborhood_id = $neighbourhood->_id;
                $userNeighborhood->save();
                $userObj = new \stdClass();
                $userObj->neighbourhoodStatus = $neighbourhood->neighbourhoodStatus->nstatus_name;
            /*}catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }*/
            $message = Lang::get('api.neighborhood_create');
            return $this->sucessResponse(true, $message, $neighbourhood);
            /*} else {
                $message = Lang::get('api.neighborhood_exists');
                return $this->errorResponse(false, $message, []);
            }*/
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Area shap is incorrect, Please draw correct shap!']);
        }
    }

    /**
     * User Join the Neighbourhood by neighborhood_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinNeighborhood(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'neighborhood_id' => 'required|exists:neighborhoods,_id',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse(false, $validator, $data = []);
        }

        $already_added = UserNeighborhood::where('neighborhood_id',$request->neighborhood_id)->where("user_id",Auth::id())->count();
        if($already_added == 0){
            $userNeighborhood = new UserNeighborhood();
            $userNeighborhood->user_id = Auth::id();
            $userNeighborhood->neighborhood_id = $request->neighborhood_id;
            $userNeighborhood->save();
            $this->neighborhoodTotalUsers($request->neighborhood_id);
            $neighbourhoods = Neighborhood::find($request->neighborhood_id);
            dispatch(new NeighbourhoodNotification($neighbourhoods, 'neighbourhood', Config::get('constant.join_neighbourhood')))->delay(now()->addSecond(1));
        }

        $message = Lang::get('api.neighborhood_join_message');
        return $this->sucessResponse(true, $message, []);
    }

    /**
     * Search the Neighbourhood
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        try {
            $perPage = 10;
            $user = Auth::user();
            $longitude = (float)$user->user_address_longitude;
            $latitude = (float)$user->user_address_latitude;
            $neighbourPoints = [$longitude, $latitude];
            $where = ["neighborhood_area" =>
                [
                    '$geoNear' => [
                        '$geometry' =>
                            [
                                'type' => 'Point', 'coordinates' => $neighbourPoints
                            ],
                        '$maxDistance' => 10000
                    ]
                ]];
            $message = "Neighbour area has been fetch";
            $result = Neighborhood::where('verified_by_admin', 1)->whereRaw($where)->get();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $itemCollection = collect($result);
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
            return $this->sucessResponse(true, $message, $paginatedItems);
        } catch (\Exception $e) {
            return $this->exceptionResponse();
        }

    }

    /**
     * Get Neighbourhood users list
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighborhoodUserLists($paginate = true)
    {
        try {
            $user_neighborhoods = Auth::user()->neighborhoods;
            if (count($user_neighborhoods) != 0) {

                $neighborhood_id = $user_neighborhoods[0]->neighborhood_id;

                $users = User::whereHas('neighborhoods', function ($query) use ($neighborhood_id) {
                    $query->where('neighborhood_id', $neighborhood_id);
                    $query->where('user_id','!=' ,Auth::id());
                });

                if($paginate=='true'){
                    $users = $users->paginate(500);
                }else{
                    $users = $users->get();
                }
                $users->map(function ($user) use($neighborhood_id) {
                    $user['unread_message'] = Chatthreads::where('cthread_from_user_id',$user->_id)
                        ->where('cthread_to_user_id',Auth::id())
                        ->where('is_seen',false)->count();
                    $is_favorite = UserNeighborhoodFavorite::where('neighborhood_id',$neighborhood_id)
                        ->where('user_id',$user->_id)->where('from_user_id',Auth::id())->first();

                    if(!empty($is_favorite)){

                        $user['is_favorite'] = true;

                    }else{

                       $user['is_favorite'] = false; 

                    }
                    return $user;
                });
                $message = Lang::get('api.neighborhood_users_message');
                return $this->sucessResponse(true, $message, $users);
            } else {
                $message = Lang::get('api.neighborhood_empty_users');
                return $this->errorResponse(false, $message, []);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

    }



    /**
     * Make Favorite from Contact list
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighborhoodFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'neighborhood_id' => 'required|exists:neighborhoods,_id',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationResponse(false, $validator, $data = []);
        }


        $exist_user = UserNeighborhood::where('neighborhood_id',$request->neighborhood_id)->where("user_id",$request->user_id)->first();

        $part_neighbourhood = UserNeighborhood::where('neighborhood_id',$request->neighborhood_id)->where("user_id",Auth::id())->first();

        if(!empty($exist_user) && !empty($part_neighbourhood)){

            $already_favorite = UserNeighborhoodFavorite::where('neighborhood_id',$request->neighborhood_id)->where('user_id',$request->user_id)->where('from_user_id',Auth::id())->first();

            if(!empty($already_favorite)){

                UserNeighborhoodFavorite::where('_id',$already_favorite->id)->delete();
                $message = Lang::get('api.neighborhood_make_unfavorite');
                return $this->sucessResponse(true, $message, []);

            }else{

                $favorite['user_id'] = $request->user_id;
                $favorite['from_user_id'] = Auth::id();
                $favorite['neighborhood_id'] = $request->neighborhood_id;

                UserNeighborhoodFavorite::insert($favorite);

                $message = Lang::get('api.neighborhood_make_favorite');
                return $this->sucessResponse(true, $message, []);
            }
        }else{
                $message = Lang::get('api.neighborhood_not_found');
                return $this->sucessResponse(false, $message, []);
        }


    }


    /**
     * User leave the Neighbourhood by neighborhood_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaveNeighborhood(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'neighborhood_id' => 'required|exists:neighborhoods,_id',
            'device_id' => 'required|exists:devices,device_id',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse(false, $validator, $data = []);
        }
        $user_status = UserStatus::where('ustatus_name','deactivate')->first();

        $already_added = UserNeighborhood::where('neighborhood_id',$request->neighborhood_id)->where("user_id",Auth::id())->count();
        if($already_added != 0){

           User::where('_id', Auth::id())->update(array('ustatus_id'=>$user_status->id));
        }

        $device = Device::where('device_id',$request->device_id)->first();

        if ($device) {
            Device::where('device_id',$request->device_id)->delete();
        }

        $message = Lang::get('api.neighborhood_leave_message');
        return $this->sucessResponse(true, $message, []);
    }



}
