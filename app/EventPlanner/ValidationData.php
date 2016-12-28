<?php
namespace App\EventPlanner;

/**
 * Validation rules for the Event Planner site.
 * @author Spencer
 *
 */
class ValidationData extends \App\ValidationData{
	public function __construct(){
		$this->validationData = [
			'register' => [
				'name' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users',
				'password' => 'required|min:6|confirmed'						
			]
		];
	}
}