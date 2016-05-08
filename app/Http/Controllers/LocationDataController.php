<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\YelpClient;

class LocationDataController extends Controller
{
	/**
	 * Handle the request where location data is posted so that data about the area will be returned
	 */
	public function postLocation(Request $request){
		$this->validate($request, [
			'location' => 'required|geolocation'	
		]);
		
		$location = $request->input('location');		
		$positions = explode(',', $location);
		$locationData = [];
		
		$locationData['lat'] = $positions[0];
		$locationData['lng'] = $positions[1];
		
		$yelpclient = new YelpClient($locationData);
		
		if($yelpclient->status !== 'error'){
			$yelpclient->query_api();
		}
		
		return response()->json([
				'data' => [
					'yelp' => $yelpclient->data 
				], 
				'status' => 'success'				
			]
		);
		
	}
}
