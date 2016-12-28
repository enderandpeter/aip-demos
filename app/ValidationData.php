<?php
namespace App;

/**
 * Manage the validation rules for a site. 
 * 
 * Each site should extend this class to provide its own rules.
 * 
 * @author Spencer
 *
 */
class ValidationData{
	protected $validationData = array();
	
	/**
	 * Get the validation data array
	 *
	 * @param string $key Optional key on which to retrieve validation data
	 * @return array
	 */
	public function getData($key = ''){
		if(isset($this->validationData[$key])){
			return $this->validationData[$key];
		}
		
		return $this->validationData;
	}
}