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
				'password' => 'required|min:6|max:255|confirmed',
				'email' => 'required|email|max:255|unique:eventplanner_users'						
			]
		];
	}
}