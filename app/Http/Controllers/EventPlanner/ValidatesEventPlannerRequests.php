<?php
namespace App\Http\Controllers\EventPlanner;

use App\EventPlanner\ValidationData;

trait ValidatesEventPlannerRequests{
	/**
	 * Get the array of validation messages to be shown on the front end
	 * 
	 * @param string $validationDataType The type of validation occurring ( 'register', 'create-event', etc. )
	 * @return void|array
	 */
	public function getValidationMessagesArray( $validationDataType = '' ){
		if( empty( $validationDataType ) ){
			return;
		}		
		/*
		 * Get the validation messages for each input in the register form so they can be loaded and
		 * shown by the frontend validation.
		 */
		$validationData = new ValidationData();
		$validationRules = $validationData->getData( $validationDataType );
		
		$messages = [];
		foreach( $validationRules as $name => $ruleString ){
			$messageTemplate = [ 'attribute' => $name ];
			$rules = explode( '|', $ruleString );
			foreach( $rules as $rule ){
				if( strpos( $rule, ':' ) !== false ){
					$rulename = explode( ':', $rule )[ 0 ];
				} else {
					$rulename = $rule;
				}
				$messageName = "validation.$rulename";
				switch( $rulename ){
					case 'unique':
						continue 2;
					case 'regex':
						$messageName = "validation.eventplanner_password";
						break;
					case 'min':
						$messageTemplate = $messageTemplate + ['min' => explode( ':', $rule )[ 1 ] ];
						$messageName .= ".string";
						break;
					case 'max':
						$messageTemplate = $messageTemplate + [ 'max' => explode( ':', $rule )[ 1 ] ];
						$messageName .= ".string";
						break;
				}
				$messages[ $name ][ $rulename ] = trans( $messageName, $messageTemplate );
			}
		}
		
		return $messages;
	}
}