<?php
namespace App;

use App\OAuth\OAuthToken;
use App\OAuth\OAuthConsumer;
use App\OAuth\OAuthRequest;
use App\OAuth\OAuthSignatureMethod_HMAC_SHA1;
/*
 * This is a remade version of the sample.php script found in Yelp's example usage of their API:
 * https://github.com/Yelp/yelp-api/blob/master/v2/php/sample.php
 * 
 * 
*/

/**
 * Retrives data from the Yelp Search and Business APIs.
 * 
 * @author Spencer
 *
 */
class YelpClient{
	// Set your OAuth credentials here
	// These credentials can be obtained from the 'Manage API Access' page in the
	// developers documentation (http://www.yelp.com/developers)

	private static $CONSUMER_KEY = 'GJZTma4Bu1w8CduRcPGmWQ';
	private static $CONSUMER_SECRET = 'FMafvPZvUgh5r80Y4bSqGzrdTeU';
	private static $TOKEN = 'TwJz7GXu42SOHiB4O05_6S8m7svTPn2H';
	private static $TOKEN_SECRET = 'XV5j9XLYU6Co7nHhOR8KmLZKDmE';
	private static $API_HOST = 'api.yelp.com';
	private static $SEARCH_LIMIT = 3;
	private static $SEARCH_PATH = '/v2/search/';
	private static $BUSINESS_PATH = '/v2/business/';

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
	}

	/**
	 * Get the location data in string format
	 */
	public function getLocationString(){
		return $this->location['lat'] . ',' . $this->location['lng'];
	}
	
	/**
	 * Makes a request to the Yelp API and returns the response
	 *
	 * @param    $path    The path of the APi after the domain
	 * @return   The JSON response from the request
	 */
	public function request($path) {
		$unsigned_url = "https://" . self::$API_HOST . $path;
		// Token object built using the OAuth library
		$token = new OAuthToken(self::$TOKEN, self::$TOKEN_SECRET);
		// Consumer object built using the OAuth library
		$consumer = new OAuthConsumer(self::$CONSUMER_KEY, self::$CONSUMER_SECRET);
		// Yelp uses HMAC SHA1 encoding
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		$oauthrequest = OAuthRequest::from_consumer_and_token(
				$consumer,
				$token,
				'GET',
				$unsigned_url
				);

		// Sign the request
		$oauthrequest->sign_request($signature_method, $consumer, $token);

		// Get the signed URL
		$signed_url = $oauthrequest->to_url();

		// Send Yelp API Call
		try {
			$ch = curl_init($signed_url);
			if (FALSE === $ch)
				throw new Exception('Failed to initialize');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$data = curl_exec($ch);
				if (FALSE === $data)
					throw new Exception(curl_error($ch), curl_errno($ch));
					$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if (200 != $http_status)
						throw new Exception($data, $http_status);
						curl_close($ch);
		} catch(Exception $e) {
			trigger_error(sprintf(
					'Curl failed with error #%d: %s',
					$e->getCode(), $e->getMessage()),
					E_USER_ERROR);
		}

		return $data;
	}
	/**
	 * Query the Search API by a search term and Lat/Long coordinates
	 *
	 * @param    $term        The search term passed to the API
	 * @param    $ll    The search position passed to the API
	 * @return   The JSON response from the request
	 */
	public function search() {
		$url_params = array();

		$url_params['ll'] = $this->getLocationString();
		$url_params['limit'] = self::$SEARCH_LIMIT;
		$search_path = self::$SEARCH_PATH . "?" . http_build_query($url_params);

		return $this->request($search_path);
	}
	/**
	 * Query the Business API by business_id
	 *
	 * @param    $business_id    The ID of the business to query
	 * @return   The JSON response from the request
	 */
	public function get_business($business_id) {
		$business_path = self::$BUSINESS_PATH . urlencode($business_id);

		return $this->request($business_path);
	}
	/**
	 * Queries the API with the input values from the user
	 *
	 * @return array An array of response JSON data for every business queried
	 */
	public function query_api() {
		$response = json_decode($this->search());

		if(json_last_error()){
			$this->status = 'error';
			$this->data = ['message' => 'Could not parse Yelp Search response data'];
			return;
		}

		$business_responses = [];
        
        foreach($response->businesses as $index => $business){
            $business_responses[] = json_decode($this->get_business($business->id));
        }
        
        $this->data = $business_responses;
        $this->status = 'success';
	}
	
	public function getResponseData(){
		return $this->data;
	}
}