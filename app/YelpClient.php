<?php
namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Retrieves data from the Yelp Search and Business APIs.
 * 
 * @author Spencer
 *
 */
class YelpClient{
	private static $BUSINESS_PATH = 'businesses/search';

	private $location = [];

	public $status = '';
	public $data = [];
	
	public function __construct($location = []){
		if(empty($location)){
			$this->status = 'error';
			$this->data = ['message' => 'A location must be provided'];
			return;
		}

		$this->location = $location;
        $this->yelp_api_client = new Client(
            [
                'base_uri' => env('YELP_API_BASE_URI'),
                'headers' => [
                    'Authorization' => 'Bearer ' . env('YELP_API_KEY'),
                    'Accept' => 'application/json'
                ],
                'query' => [
                    'latitude' => $this->location['lat'],
                    'longitude' => $this->location['lng']
                ]
            ]
        );
	}

	/**
	 * Makes a request to the Yelp API and returns the response
	 *
	 * @param    $path    The path of the APi after the domain
	 * @return   The JSON response from the request
	 */
	public function request($path) {
		try{
            $response = $this->yelp_api_client->get($path);

            if($response->getStatusCode() !== 200){
                echo 'Error code ' . $response->getStatusCode() . 'with response from Yelp: ' . $response->getBody();
            }

            $data = $response->getBody();
        } catch(RequestException $e){
		    $this->status = 'error';
		    echo 'Error code ' . $e->getCode() . 'with response from Yelp: ' . $e->getMessage();
        }

		return $data;
	}
	/**
	 * Get a JSON array of businesses at the specified location
	 *
	 * @return string A JSON response that should be an object with property "reviews" that is the list of reviews
	 */
	public function get_businesses() {
		/*
		 * Get a list of businesses in the area
		 */

		return $this->request(self::$BUSINESS_PATH);
	}

	/**
	 * Create a data structure of Yelp business found in the area with their corresponding reviews
	 *
	 * @return void
	 */
	public function query_api() {
        $businesses_response = json_decode($this->get_businesses());

		if(json_last_error()){
			$this->status = 'error';
			$this->data = ['message' => 'Could not parse Yelp Business Search response data'];
			return;
		}

		if(!property_exists($businesses_response, 'businesses')){
		    $this->status = 'error';
            $this->data = ['message' => 'Businesses property not found'];
            return;
        }

        $businesses = collect($businesses_response->businesses);

        $businesses->transform(function($business){
            $id = $business->id;
            $reviews_response = json_decode($this->request("businesses/$id/reviews"));

            if(json_last_error()){
                $this->status = 'error';
                $this->data = ['message' => 'Could not parse Yelp Reviews Search response data'];
                return;
            }

            if(!property_exists($reviews_response, 'reviews')){
                $this->status = 'error';
                $this->data = ['message' => 'Reviews property not found'];
                return;
            }

            $business->reviews = $reviews_response->reviews;

            return $business;

        });

        $this->data = $businesses->toArray();
        $this->status = 'success';
	}
}