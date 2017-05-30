<?php
namespace App\EventPlanner;

use Illuminate\Support\Facades\Auth;
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
			],
			'create-event' => [
				'name' => 'required|max:255',
				'type' => 'required|max:191',
				'host' => 'required|max:191',
				'start_date' => 'required|max:255|date_format:m/d/Y g:i a|before_or_equal:end_date',
				'end_date' => 'required|max:255|date_format:m/d/Y g:i a|after_or_equal:start_date',
				'guest_list' => 'required|max:1000',
				'location' => 'required|max:191',
				'guest_message' => 'max:5000'
			]
		];
	}
}