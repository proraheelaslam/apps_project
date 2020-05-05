<?php

namespace App\Http\Controllers\admin;

use App\Models\EmailTemplate;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LocationDropDownController extends Controller
{
   public function getCountries(){
   		try{
   			$countries = Country::all();
   			return $countries;
   		}catch(\Exception $e){
   			return [];
   		}
   }
   public function getStates($countryId){
   		try{
   			$states = State::where("country_id", $countryId)->get();
   			return response()->json(["status" => true, "message" => "States found.", "data" => $states], 200);
   		}catch(\Exception $e){
   			return response()->json(["status" => false, "message" => "States not found."], 400);
   		}
   }
   public function getCities($stateId){
   		try{
   			$cities = City::where("state_id", $stateId)->get();
   			
   			return response()->json(["status" => true, "message" => "Cities found.", "data" => $cities], 200);
   		}catch(\Exception $e){
   			return response()->json(["status" => false, "message" => "Cities not found."], 400);
   		}
   }
   public function test($stateId){
   		$stateId = (string) $stateId;
   		$states = City::where("state_id", (string) 1)->get();
   		return $states;
   }
}
