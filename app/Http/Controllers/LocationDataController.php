<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\YelpClient;

class LocationDataController extends Controller
{
	/**
	 * Handle the request where location data is posted so that data about the area will be returned
	 */
	public function getLocation(Request $request){
		$this->validate($request, [
			'location' => 'required|geolocation'
		]);

		$location = $request->input('location');
		$positions = explode(',', $location);
		$locationData = [];

		$locationData['lat'] = $positions[0];
		$locationData['lng'] = $positions[1];

		$yelpclient = app(YelpClient::class, ['location' => $locationData]);

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
